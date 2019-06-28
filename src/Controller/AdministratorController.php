<?php

namespace App\Controller;

use phpDocumentor\Reflection\Types\String_;
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
     * @Route("/administrator/listVehicle/{param}", name="listVehicle")
     */
    public function listVehicle(string $param)
    {
        return $this->render('administrator/listVehicle.html.twig', [

            'controller_name' => 'Affiche de la liste des vehicules disponnible'
        ]);
    }
}
