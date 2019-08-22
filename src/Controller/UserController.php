<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditUserType;
use App\Repository\RentalRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/user")
 * @IsGranted("ROLE_USER")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/myAccount", name="user__myAccount")
     * get User Info
     */
    public function myAccount(UserRepository $userRepository)
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
            'user' => $this->getUser(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit_account")
     * @ParamConverter("user", options={"id" = "id"})
     */
    public function editMyAccount(User $user, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {

        /**
         * Edition des infos d'un utilisateur (Role y compris (=> choisir un CarDealer si employé))
         */
        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('user__myAccount', [
                'id' => $user->getId()
            ]);
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete", name="delete_myAccount")
     */
    public function deleteMyAccount(EntityManagerInterface $entityManager, RentalRepository $rentalRepository)
    {
        $user = $this->getUser();
        /**
         * Supprime un utilisateur
         */


        foreach ($rentalRepository->findBy(array('client' => $user->getId())) as $rental)
        {
            if (!$rental->getRealReturnDate()){
                $this->addFlash('danger', 'Vous avez une ou plusieurs location(s) en cours');
                return $this->render('user/index.html.twig', [
                    'user' => $this->getUser(),
                ]);
            }
        }

        $entityManager->remove($user);
        $entityManager->flush();
        $this->get('security.token_storage')->setToken(null);
        $this->get('session')->invalidate();
        return $this->redirectToRoute('logout');
    }
}
