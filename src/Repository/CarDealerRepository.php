<?php

namespace App\Repository;

use App\Entity\CarDealer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CarDealer|null find($id, $lockMode = null, $lockVersion = null)
 * @method CarDealer|null findOneBy(array $criteria, array $orderBy = null)
 * @method CarDealer[]    findAll()
 * @method CarDealer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarDealerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CarDealer::class);
    }

    // /**
    //  * @return CarDealer[] Returns an array of CarDealer objects
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
    public function findOneBySomeField($value): ?CarDealer
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
