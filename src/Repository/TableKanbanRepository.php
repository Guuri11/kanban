<?php

namespace App\Repository;

use App\Entity\TableKanban;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TableKanban|null find($id, $lockMode = null, $lockVersion = null)
 * @method TableKanban|null findOneBy(array $criteria, array $orderBy = null)
 * @method TableKanban[]    findAll()
 * @method TableKanban[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TableKanbanRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TableKanban::class);
    }

    // /**
    //  * @return TableKanban[] Returns an array of TableKanban objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TableKanban
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
