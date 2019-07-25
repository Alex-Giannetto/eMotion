<?php

namespace App\Repository;

use App\Entity\Vehicle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Vehicle|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vehicle|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vehicle[]    findAll()
 * @method Vehicle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VehicleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Vehicle::class);
    }

    public function listVehicleEmployeeConcession(){

        $vehicule = $this->getEntityManager()->createQuery("SELECT * 
        FROM vehicle v , user u 
        WHERE v.id = u.id
        AND v.car_dealer_id = u.car_dealer_id");

        return $vehicule;
    }

    public function editVehicle(){

        $vehicule = $this->getEntityManager()->createQuery("
        UPDATE vehicule
        SET ");

        return $vehicule;

    }



}
