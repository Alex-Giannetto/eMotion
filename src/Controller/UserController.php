<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 * @IsGranted("ROLE_USER")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user__myAccount")
     */
    public function myAccount()
    {
        /**
         * Page 'Mon Compte' de l'utilisateur.
         * Sur cette page l'utilisateur aura :
         *  - ses prochaine + location en cours
         *   - factures de ses dernieres location
         *  - modifier ses infos
         *  - son nombre de point
         *  …
         * Cette page nécéssite d'être connecté.
         */

        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/account/edit", name="user__editAccount")
     */
    public function editMyAccount()
    {

        /**
         * Page d'édition du profile de l'utilisateur
         * Sur cette page l'utilisateur pourra éditer toutes ses infos personnelles
         */

        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/account/delete", name="user__deleteAccount")
     */
    public function deleteMyAccount()
    {

        /**
         * RGPD : suppression du compte utilisateur ainsi que de toutes ses données (factures ?)
         * Après une verification (l'utilisateur écrit son email ?) on supprime ses données
         * (redirection sur la route /logout)
         */

        $check = false;

        if ($check) {
            return $this->redirectToRoute('logout');
        }

        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}
