<?php

namespace App\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/user")
 * @IsGranted("ROLE_ADMIN")
 */
class UserController extends AbstractController
{

    /**
     * @Route("/", name="admin__user__list")
     * @IsGranted("ROLE_ADMIN")
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
     * @Route("/delete/{id}", name="admin__user__delete")
     * @IsGranted("ROLE_ADMIN")
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
     * @Route("/edit/{}", name="admin__user__edit")
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
