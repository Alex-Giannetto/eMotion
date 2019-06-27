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
        return $this->render('administrator/edit.html.twig',[
            'controller_name' => 'Partie Edit'
        ]);
    }
}
