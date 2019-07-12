<?php

namespace App\Controller\BackOffice;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/bo/user")
 * @IsGranted("ROLE_ADMIN")
 */
class UserController extends AbstractController
{

    /**
     * @Route("/", name="bo__user__list")
     */
    public function listUser()
    {

        /**
         * Liste de tout les utilisateurs
         */

        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/delete/{id}", name="bo__user__delete")
     */
    public function deleteUser()
    {

        /**
         * Supprime un utilisateur
         */

        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/edit/{id}", name="bo__user__edit")
     */
    public function editUser()
    {

        /**
         * Edition des infos d'un utilisateur (Role y compris (=> choisir un CarDealer si employé))
         */

        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

}
