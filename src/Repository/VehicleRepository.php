<?php

namespace App\Repository;

use App\Entity\Vehicle;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Vehicle|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vehicle|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vehicle[]    findAll()
 * @method Vehicle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VehicleRepository extends ServiceEntityRepository
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(RegistryInterface $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Vehicle::class);
        $this->entityManager = $entityManager;
    }


    public function getAvailableVehicles(int $idType, int $idLocation, DateTime $start, DateTime $end)
    {

        return $this->createQueryBuilder('v')
            ->leftJoin('App\Entity\Rental', 'r', 'WITH', 'v = r.vehicle')
            ->where('v.state = 1')
            ->andWhere('v.vehicleType = :idType')
            ->andWhere('v.carDealer = :idLocation')
            ->andWhere('r is null or NOT (
                r.startRentalDate  BETWEEN :start AND :end
                OR r.estimatedReturnDate  BETWEEN :start AND :end
                OR(r.startRentalDate < :start AND r.estimatedReturnDate > :end))')
            ->orderBy('v.dailyPrice')
            ->setParameter(':idType', $idType)
            ->setParameter(':idLocation', $idLocation)
            ->setParameter(':start', $start->format('Y-m-d'))
            ->setParameter(':end', $end->format('Y-m-d'))
            ->getQuery()
            ->getResult();

    }
}
