<?php

namespace App\Repository;

use App\Entity\Professionnel;
use App\Entity\Transaction;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Transaction>
 */
class TransactionRepository extends ServiceEntityRepository
{
    use TableInfoTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

    public function add(Transaction $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function nextNumero($annee)
    {
        $data = $this->lastNumero($annee);
        if ($data && $data['reference']) {
            $reference = $data['reference'];
           
            if (strpos($reference, '-') !== false) {
                [, $numero] = explode('-', $reference);
                $numero = ltrim($numero, '0');
            } else {
                $numero = 0;
            }
        } else {
            $numero = 0;
        }

   
        $code = "UP";
        $chrono = str_pad($numero + 1 , 4, '0', STR_PAD_LEFT);
        $annee = substr($annee, -2);


        
        return "{$code}{$annee}-{$chrono}";
    }

  


    public function getHistorique()
    {
        $em = $this->getEntityManager();
        $connection = $em->getConnection();
        $transaction = $this->getTableName(Transaction::class, $em);
        $user = $this->getTableName(User::class, $em);
        $professionnel = $this->getTableName(Professionnel::class, $em);
 

        $sql = <<<SQL
        SELECT *
        FROM {$transaction} p
        JOIN {$user} u ON u.id = p.user_id
        /* JOIN {$professionnel} pr ON pr.user_id = u.id */
        SQL;

        $stmt = $connection->executeQuery($sql);

        return $stmt->fetchAllAssociative();
    }

    //    /**
    //     * @return Transaction[] Returns an array of Transaction objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Transaction
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
