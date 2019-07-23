<?php

namespace App\Controller\BackOffice;

use App\Entity\User;
use App\Form\RegisterType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/bo/user")
 * @IsGranted("ROLE_ADMIN")
 */
class UserController extends AbstractController
{

    /**
     * @Route("/", name="bo__user__list")
     * List of users
     */
    public function listUser(UserRepository $userRepository)
    {
        return $this->render('bo/user/list.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/info/{id}", name="bo__user__info")
     * @ParamConverter("user", options={"id" = "id"})
     * get User Info
     */
    public function userInfo(User $user)
    {
        return $this->render('bo/user/info.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/delete/{id}", name="bo__user__delete")
     * @ParamConverter("user", options={"id" = "id"})
     */
    public function deleteUser(User $user, EntityManagerInterface $entityManager)
    {
        /**
         * Supprime un utilisateur
         */

        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('bo__user__list');
    }

    /**
     * @Route("/edit/{id}", name="bo__user__edit")
     */
    public function editUser(Request $request)
    {

        /**
         * Edition des infos d'un utilisateur (Role y compris (=> choisir un CarDealer si employé))
         */
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('login');
        }

        return $this->render('bo/user/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
