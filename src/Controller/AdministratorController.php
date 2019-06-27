<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdministratorController extends AbstractController
{
    /**
     * @Route("/administrator", name="administrator")
     */
    public function index()
    {
        return $this->render('administrator/index.html.twig', [
            'controller_name' => 'AdministratorController',
        ]);
    }

    /**
     * @Route("/administrator/edit", name="editAdministrator")
     */
    public function editClient()
    {
        return $this->render('administrator/editClient.html.twig',[
            'controller_name' => 'Partie Edit'
        ]);
    }

    /**
     * @Route("/administator/listClient", name="listClient")
     */
    public function listClient()
    {
        return $this->render('administrator/listClient.html.twig', [
            'controller_name' => 'Affichage liste utilisateurs'
        ]);
    }

    /**
     * @Route("/administrator/listVehicleOk", name="listVehicle")
     */
    public function listVehicleOk()
    {
        return $this->render('administrator/listVehicleOk.html.twig', [
            'controller_name' => 'Affiche de la liste des vehicules disponnible'
        ]);
    }

    /**
     * @Route("/administrator/listVehicleNone", name="listVehicle")
     */
    public function listVehicleNone()
    {
        return $this->render('administrator/listVehicleNone.html.twig', [
            'controller_name' => 'Affiche de la liste des vehicules louer'
        ]);
    }
}
