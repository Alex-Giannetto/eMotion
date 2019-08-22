<?php

namespace App\Controller;

use App\Entity\CarDealer;
use App\Entity\VehicleType;
use App\Repository\CarDealerRepository;
use App\Repository\VehicleTypeRepository;
use App\Service\MailService;
use DateTime;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(
        CarDealerRepository $carDealerRepository,
        VehicleTypeRepository $vehicleTypeRepository,
        Request $request
    ) {

        $form = $this->createFormBuilder()
            ->add(
                'location',
                EntityType::class,
                [
                    'class' => CarDealer::class,
                    'choice_label' => 'name',
                    'required' => true,
                    'label' => 'Lieux',
                ]
            )
            ->add(
                'start',
                null,
                [
                    'attr' => ['class' => 'js-datepicker'],
                    'required' => true,
                    'label' => 'Début',
                ]
            )
            ->add(
                'end',
                null,
                [
                    'attr' => ['class' => 'js-datepicker'],
                    'required' => true,
                    'label' => 'Fin',
                ]
            )
            ->add(
                'type',
                EntityType::class,
                [
                    'class' => VehicleType::class,
                    'choice_label' => 'label',
                    'required' => true,
                    'label' => 'Type',
                ]
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'Chercher',
                ]
            )
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            return $this->redirectToRoute(
                'rental__search',
                [
                    'carDealer' => $form->getData()['location']->getId(),
                    'dateStart' => DateTime::createFromFormat(
                        'd/m/Y',
                        $form->getData()['start']
                    )->format('Y-m-d'),
                    'dateEnd' => DateTime::createFromFormat(
                        'd/m/Y',
                        $form->getData()['end']
                    )->format('Y-m-d'),
                    'vehicleType' => $form->getData()['type']->getId(),
                ]
            );
        }

        return $this->render(
            'default/index.html.twig',
            [
                'carDealer' => $carDealerRepository->findAll(),
                'carType' => $vehicleTypeRepository->findAll(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/services", name="services")
     */
    public function services()
    {
        return $this->render(
            'default/services.html.twig',
            [
                'controller_name' => 'DefaultController',
            ]
        );
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request, MailService $mailService)
    {
        $form = $this->createFormBuilder()
            ->add(
                'subject',
                TextType::class,
                [
                    'label' => 'Sujet',
                    'required' => true,
                ]
            )
            ->add(
                'firstname',
                TextType::class,
                [
                    'label' => 'Prénom',
                    'required' => true,
                ]
            )
            ->add(
                'lastname',
                TextType::class,
                [
                    'label' => 'Nom',
                    'required' => true,
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                    'label' => 'Email',
                    'required' => true,
                ]
            )
            ->add(
                'message',
                TextareaType::class,
                [
                    'label' => 'Message',
                    'required' => true,
                    'attr' => ['rows' => '10'],
                ]
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'Envoyer',
                ]
            )
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid() && !$form->isSubmitted()) {
            return $this->render(
                'default/contact.html.twig',
                [
                    'form' => $form->createView(),
                ]
            );
        }

        $mailService->sendMailContact(
            $form->getData()['firstname'],
            $form->getData()['lastname'],
            $form->getData()['email'],
            $form->getData()['message']
        );

        $this->addFlash(
            'success',
            'Votre demande de contact a bien été envoyée. Vous recevrez une copie par mail'
        );

        return $this->redirectToRoute('home');
    }
}
