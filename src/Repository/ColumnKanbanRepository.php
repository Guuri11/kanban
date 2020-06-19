<?php

namespace App\Repository;

use App\Entity\ColumnKanban;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ColumnKanban|null find($id, $lockMode = null, $lockVersion = null)
 * @method ColumnKanban|null findOneBy(array $criteria, array $orderBy = null)
 * @method ColumnKanban[]    findAll()
 * @method ColumnKanban[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ColumnKanbanRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ColumnKanban::class);
    }

    // /**
    //  * @return ColumnKanban[] Returns an array of ColumnKanban objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ColumnKanban
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
