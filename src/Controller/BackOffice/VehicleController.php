<?php

namespace App\Controller\BackOffice;

use App\Entity\Vehicle;
use App\Repository\VehicleRepository;
use App\Form\EditVehicleType;
use Doctrine\ORM\EntityManagerInterface;
use http\Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
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

        if($this->isGranted("ROLE_ADMIN")){

            return $this->render('vehicle/list.html.twig', [
                'vehicles' => $vehicleRepository->findall(),
            ]);

        } else if($this->isGranted("ROLE_EMPLOYEE")){

            return $this->render('vehicle/list.html.twig', [
                'vehicles' => $vehicleRepository->listVehicleEmployeeConcession(),
            ]);

        }
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
     */
    public function editVehicle(Vehicle $vehicle , Request $request){

        /**
         * Vérifier qu'utilisateur a le droit de modifier ce vehicule
         * Modifier les données d'un véhicule
         */

        $form = $this->createForm(EditVehicleType::class, $vehicle);
        $form->handleRequest($request);

        if($this->isGranted("ROLE_ADMIN")){
            if($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
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
     */
    public function addVehicle(){

        /**
         * Formulaire d'ajout d'un vehicle.
         * Si Employee => formulaire sans les CarDealer
         * Si Admin => Formulaire avec CarDealer
         */

        $entityManager = $this->getDoctrine()->getManager();

        $vehicule = new Vehicle();

        $entityManager->persist($vehicule);

        $entityManager->flush();


        return $this->render('vehicle/list.html.twig', [
            'controller_name' => 'VehicleController',
        ]);
    }

    /**
     * @Route("/delete/{id}" , name="bo__vehicle__delete")
     * @ParamConverter("vehicle", options={"id" = "id"})
     */
    public function deleteVehicle(Vehicle $vehicle, EntityManagerInterface $entityManager) {

        /**
         * Fonction de suppression de véhicule et vérifier s'il peut les supprimer.
         */

        $entityManager->remove($vehicle);
        $entityManager->flush();

        return $this->redirectToRoute('bo__vehicle__list');
    }
}
