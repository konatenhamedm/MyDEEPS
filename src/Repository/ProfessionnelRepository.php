<?php

namespace App\Repository;

use App\Entity\Civilite;
use App\Entity\Professionnel;
use App\Entity\Ville;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Professionnel>
 */
class ProfessionnelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Professionnel::class);
    }

    public function add(Professionnel $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Professionnel $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getProfessionnelByetat($status)
    {

        return $this->createQueryBuilder('p')
            ->innerJoin('p.user', 'user')
            ->andWhere('user.status = :val')
            ->setParameter('val', $status)
            ->getQuery()
            ->getResult();
    }
    public function allProfAjour()
    {

        return $this->createQueryBuilder('p')
            ->innerJoin('p.user', 'user')
            ->andWhere('user.status = :val')
            ->andWhere('user.payement = :payement')
            ->setParameter('payement', 'payed')
            ->setParameter('val', 'ACCEPT')
            ->getQuery()
            ->getResult();
    }
    public function countProByCivilite()
    {

        return $this->getEntityManager()->createQueryBuilder()
        ->select('c.libelle AS civilite, COUNT(e.id) AS nombre')
        ->from(Civilite::class, 'c')
        ->leftJoin(Professionnel::class, 'e', 'WITH', 'e.civilite = c.id')
        ->groupBy('c.id')
        ->getQuery()
        ->getResult();

    }
    public function countProByVille()
    {

        return $this->getEntityManager()->createQueryBuilder()
        ->select('c.libelle AS civilite, COUNT(e.id) AS nombre')
        ->from(Ville::class, 'c')
        ->leftJoin(Professionnel::class, 'e', 'WITH', 'e.ville = c.id')
        ->groupBy('c.id')
        ->getQuery()
        ->getResult();

    }
    //    /**
    //     * @return Professionnel[] Returns an array of Professionnel objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Professionnel
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
