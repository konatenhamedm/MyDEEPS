<?php

namespace App\Repository;

use App\Entity\Profession;
use App\Entity\Professionnel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Profession>
 */
class ProfessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Profession::class);
    }

    public function add(Profession $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Profession $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function countSpecialiteProfByGenre($genre)
    {
   

        if($genre == "tout"){
            return $this->getEntityManager()->createQueryBuilder()
            ->select('c.libelle AS civilite, COUNT(e.id) AS nombre')
            ->from(Profession::class, 'c')
            ->leftJoin(Professionnel::class, 'e', 'WITH', 'e.profession = c.code')
             ->groupBy('c.id') 
            ->getQuery()
            ->getResult();
        }else{
            return $this->getEntityManager()->createQueryBuilder()
            ->select('c.libelle AS civilite, COUNT(e.id) AS nombre')
            ->from(Profession::class, 'c')
            ->leftJoin(Professionnel::class, 'e', 'WITH', 'e.profession = c.code')
            ->groupBy('c.id')
            ->getQuery()
            ->getResult();
        }
        

    }



    //    /**
    //     * @return Profession[] Returns an array of Profession objects
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

    //    public function findOneBySomeField($value): ?Profession
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
