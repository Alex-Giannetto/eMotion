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
    public function edit()
    {
        return $this->render('administrator/editClient.html.twig',[
            'controller_name' => 'Partie Edit'
        ]);
    }

    /**
     * @Route("/administator/list", name="listClient")
     */
    public function list()
    {
        return $this->render('administrator/listClient.html.twig', [
            'controller_name' => 'Affichage liste utilisateurs'
        ]);
    }
}
