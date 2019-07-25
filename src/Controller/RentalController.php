<?php

namespace App\Controller;

use App\Repository\VehicleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RentalController extends AbstractController
{
    /**
     * @Route("/search", name="rental__search")
     */
    public function index(Request $request, VehicleRepository $vehicleRepository)
    {
        $dateStart = $request->query->get('date_start');
        $dateEnd = $request->query->get('date_end');
        $idTypeVehicle = $request->query->get('type');
        $idLocation = $request->query->get('location');

        $vehicules = $vehicleRepository->getAvailableVehicle($idTypeVehicle, $idLocation, $dateStart, $dateEnd);

        return $this->render('rental/index.html.twig', [
            'vehicles' => $vehicules
        ]);
    }
}
