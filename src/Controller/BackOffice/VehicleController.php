<?php

namespace App\Controller\BackOffice;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/bo/vehicle")
 * @IsGranted("ROLE_EMPLOYEE")
 */
class VehicleController extends AbstractController
{
    /**
     * @Route("/", name="bo__vehicle__list")
     */
    public function listVehicle()
    {
        /**
         * Liste des véhicule :
         *  Si ROLE_ADMIN => Tout les vehicule
         *  SI ROLE_EMPLOYEE => Véhicule de sa concession
         */

        return $this->render('vehicle/index.html.twig', [
            'controller_name' => 'VehicleController',
        ]);
    }

    /**
     * @Route("/edit/{id}", name="bo__vehicle__edit")
     */
    public function editVehicle(){


        /**
         * Vérifier qu'utilisateur a le droit de modifier ce vehicule
         * Modifier les données d'un véhicule
         */

        return $this->render('vehicle/index.html.twig', [
            'controller_name' => 'VehicleController',
        ]);
    }

    /**
     * @Route("/add", name="bo__vehicle__add")
     */
    public function addVehicle(){

        /**
         * Formulaire d'ajout d'un vehicle.
         * Si Employee => formulaire sans les CarDealer
         * Si Admin => Formulaire avec CarDealer
         */


        return $this->render('vehicle/index.html.twig', [
            'controller_name' => 'VehicleController',
        ]);
    }
}
