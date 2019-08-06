<?php

namespace App\Controller;

use App\Entity\CarDealer;
use App\Entity\Rental;
use App\Entity\Vehicle;
use App\Repository\CarDealerRepository;
use App\Repository\VehicleRepository;
use App\Repository\VehicleTypeRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RentalController extends AbstractController
{
    /**
     * @Route("/search", name="rental__search")
     */
    public function search(Request $request, VehicleRepository $vehicleRepository, VehicleTypeRepository $vehicleTypeRepository, CarDealerRepository $carDealerRepository)
    {
        $dateStart = $request->query->get('date_start');
        $dateEnd = $request->query->get('date_end');
        $idTypeVehicle = $request->query->get('type');
        $idLocation = $request->query->get('location');

        $vehicles = $vehicleRepository->getAvailableVehicle($idTypeVehicle, $idLocation, $dateStart, $dateEnd);

        return $this->render('rental/index.html.twig', [
            'vehicles' => $vehicles,
            'carDealer' => $carDealerRepository->findAll(),
            'carType' => $vehicleTypeRepository->findAll(),
            'dateStart' => $dateStart,
            'dateEnd' => $dateEnd,
            'idLocation' => $idLocation,
            'idTypeVehicle' => $idTypeVehicle,
        ]);
    }


    /**
     * @Route("/overview/{dateStart}/{dateEnd}/{carDealer}/{vehicle}", name="rental__overview")
     * @ParamConverter("carDealer", options={"id" = "carDealer"})
     * @ParamConverter("vehicle", options={"id" = "vehicle"})
     */
    public function overview(string $dateStart, string $dateEnd, CarDealer $carDealer, Vehicle $vehicle, VehicleRepository $vehicleRepository)
    {

        $availableVehicleForSelectedDate = $vehicleRepository->getAvailableVehicle($vehicle->getVehicleType()->getId(), $carDealer->getId(), $dateStart, $dateEnd);

        if (!in_array($vehicle, $availableVehicleForSelectedDate)) {
            return $this->redirectToRoute('home');
        }

        $rental = new Rental();
        $rental->setClient($this->getUser());
        $rental->setVehicle($vehicle);
        $rental->setStartRentalDate(new \DateTime($dateStart));
        $rental->setEstimatedReturnDate(new \DateTime($dateEnd));
        $rental->setPrice($vehicle->getDailyPrice());

        return $this->render('rental/overview.html.twig', [
            'rental' => $rental
        ]);

    }


}
