<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function add(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function findActiveProfessionnelsByImputation(int $imputationId): array
    {
        return $this->createQueryBuilder('u')
            ->innerJoin('u.personne', 'p')
            ->leftJoin('App\Entity\Professionnel', 'pro', 'WITH', 'pro.id = p.id')
            ->andWhere('u.typeUser = :type')
            ->andWhere('p.actived = :active')
            ->andWhere('pro.imputation = :imputationId')
            ->setParameter('type', 'PROFESSIONNEL')
            ->setParameter('active', true)
            ->setParameter('imputationId', $imputationId)
            ->orderBy('u.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findActiveProfessionnelsByImputationWithouParam()
    {
        return $this->createQueryBuilder('u')
            ->innerJoin('u.personne', 'p')
            /* ->leftJoin('p.imputation', 'i') */
            ->andWhere('p.actived = :active')


            ->andWhere('u.typeUser = :type')
            ->setParameter('type', 'PROFESSIONNEL')
            ->setParameter('active', 1)
            ->orderBy('u.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return User[] Returns an array of User objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }



    public function getUserByRole()
    {

        return $this->createQueryBuilder('u')
            ->andWhere('u.roles LIKE :role')
            /* ->andWhere('u.typeUser = :typeUser') */
            ->setParameter('role', '%"ROLE_ADMIN"%')
            /*  ->setParameter('typeUser', 'ADMINISTRATEUR') */
            ->getQuery()
            ->getResult();
    }
    public function getAllProfessionnelImputation($imputation)
    {

        //$professionnels = $userRepository->findBy(['typeUser' => 'PROFESSIONNEL','imputation'=> $id], ['id' => 'DESC']);

        return $this->createQueryBuilder('u')
            ->innerJoin('u.personne', 'p')
            ->andWhere('u.typeUser = :typeUser')
            ->andWhere('p.imputation = :imputation')
            ->setParameter('imputation', $imputation)
            ->setParameter('typeUser', 'PROFESSIONNEL')
            ->orderBy('u.id ', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
