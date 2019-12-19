<?php

namespace App\Repository;

use App\Entity\RecordCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method RecordCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method RecordCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method RecordCategory[]    findAll()
 * @method RecordCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecordCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RecordCategory::class);
    }

    // /**
    //  * @return RecordCategory[] Returns an array of RecordCategory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RecordCategory
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
