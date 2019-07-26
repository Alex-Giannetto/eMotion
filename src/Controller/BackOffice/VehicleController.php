<?php

namespace App\Controller\BackOffice;

use App\Entity\Vehicle;
use App\Form\AddVehicleType;
use App\Form\EditVehicleType;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function listVehicle(VehicleRepository $vehicleRepository)
    {
        /**
         * Liste des véhicule :
         *  Si ROLE_ADMIN => Tout les vehicule
         *  SI ROLE_EMPLOYEE => Véhicule de sa concession
         */

        $vehicles = ($this->isGranted("ROLE_ADMIN")) ? $vehicleRepository->findall() : $this->getUser()->getCarDealer()->getVehicles();

        return $this->render('bo/vehicle/list.html.twig', [
            'vehicles' => $vehicles
        ]);
    }


    /**
     * @Route("/info/{id}", name="bo__vehicle__info")
     * @ParamConverter("vehicle", options={"id" = "id"})
     * get Vehicle Info
     */

    public function vehicleInfo(Vehicle $vehicle)
    {
        return $this->render('vehicle/info.html.twig', [
            'vehicle' => $vehicle
        ]);
    }

    /**
     * @Route("/edit/{id}", name="bo__vehicle__edit")
     * @ParamConverter("vehicle", options={"id" = "id"})
     */
    public function editVehicle(Vehicle $vehicle, Request $request)
    {

        /**
         * Vérifier qu'utilisateur a le droit de modifier ce vehicule
         * Modifier les données d'un véhicule
         */

        $form = $this->createForm(EditVehicleType::class, $vehicle);
        $form->handleRequest($request);

        if ($this->isGranted("ROLE_ADMIN")) { // todo : voter
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();//todo : injecter dans la fonction
                $entityManager->persist($vehicle);
                $entityManager->flush();
                return $this->redirectToRoute('bo__vehicle__info', [
                    'id' => $vehicle->getId()
                ]);
            }
        }

        return $this->render('vehicle/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/add", name="bo__vehicle__add")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response // todo : à supprimer
     */
    public function addVehicle(Request $request)
    {

        /**
         * Formulaire d'ajout d'un vehicle.
         * Si Employee => formulaire sans les CarDealer
         * Si Admin => Formulaire avec CarDealer
         */

        $vehicle = new Vehicle();
        $form = $this->createForm(AddVehicleType::class, $vehicle);
        $form->handleRequest($request);

        /* A ajouter ici condition avec carDealer et sans carDealer*/ // todo : il y a forcément un car dealer
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($vehicle);
            $entityManager->flush();
            return $this->redirectToRoute('bo__vehicle__info', [
                'id' => $vehicle->getId()
            ]);
        }

        return $this->render('vehicle/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}" , name="bo__vehicle__delete")
     * @ParamConverter("vehicle", options={"id" = "id"})
     */
    public function deleteVehicle(Vehicle $vehicle, EntityManagerInterface $entityManager)
    {

        /**
         * Fonction de suppression de véhicule et vérifier s'il peut les supprimer.
         */

        $entityManager->remove($vehicle);
        $entityManager->flush();

        return $this->redirectToRoute('bo__vehicle__list');
    }
}
