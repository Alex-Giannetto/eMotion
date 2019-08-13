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


    public function getAvailableVehicle(int $idType, int $idLocation, DateTime $start, DateTime $end)
    {
        return $this->entityManager
            ->createQuery("
                SELECT v
                FROM App\Entity\Vehicle v
                WHERE v.state = 1
                AND v.vehicleType = :idType
                AND v.carDealer = :idLocation
                AND v NOT IN (
                    SELECT r
                    FROM App\Entity\Rental r
                    WHERE r.startRentalDate BETWEEN :start AND :end
                    OR  r.estimatedReturnDate BETWEEN :start AND :end
                    OR (
                      r.startRentalDate < :start
                      and r.estimatedReturnDate > :end
                    )
                )
                ORDER BY v.dailyPrice
            ")
            ->setParameter(':idType', $idType)
            ->setParameter(':idLocation', $idLocation)
            ->setParameter(':start', $start->format('Y-m-d'))
            ->setParameter(':end', $end->format('Y-m-d'))
            ->getResult();

    }
}
