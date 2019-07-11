<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user__myAccount")
     * @IsGranted("ROLE_USER")
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


}
