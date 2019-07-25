<?php

namespace App\Controller;

use App\Repository\CarDealerRepository;
use App\Repository\VehicleTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(CarDealerRepository $carDealerRepository, VehicleTypeRepository $vehicleTypeRepository)
    {
        return $this->render('default/index.html.twig', [
            'carDealer' => $carDealerRepository->findAll(),
            'carType' => $vehicleTypeRepository->findAll(),
        ]);
    }
}
