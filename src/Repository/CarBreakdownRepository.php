<?php

namespace App\Repository;

use App\Entity\CarBreakdown;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CarBreakdown|null find($id, $lockMode = null, $lockVersion = null)
 * @method CarBreakdown|null findOneBy(array $criteria, array $orderBy = null)
 * @method CarBreakdown[]    findAll()
 * @method CarBreakdown[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarBreakdownRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CarBreakdown::class);
    }

    // /**
    //  * @return CarBreakdown[] Returns an array of CarBreakdown objects
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
    public function findOneBySomeField($value): ?CarBreakdown
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
