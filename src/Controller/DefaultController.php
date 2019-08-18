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
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
            ->add('location', EntityType::class, [
                'class' => CarDealer::class,
                'choice_label' => 'name',
                'required' => true,
                'label' => 'Lieux',
            ])
            ->add('start', null, [
                'attr' => ['class' => 'js-datepicker'],
                'required' => true,
                'label' => 'Début',
            ])
            ->add('end', null, [
                'attr' => ['class' => 'js-datepicker'],
                'required' => true,
                'label' => 'Fin',
            ])
            ->add('type', EntityType::class, [
                'class' => VehicleType::class,
                'choice_label' => 'label',
                'required' => true,
                'label' => 'Type',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Chercher',
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            return $this->redirectToRoute('rental__search', [
                'carDealer' => $form->getData()['location']->getId(),
                'dateStart' => DateTime::createFromFormat('d/m/Y',
                    $form->getData()['start'])->format('Y-m-d'),
                'dateEnd' => DateTime::createFromFormat('d/m/Y',
                    $form->getData()['end'])->format('Y-m-d'),
                'vehicleType' => $form->getData()['type']->getId(),
            ]);
        }

        return $this->render('default/index.html.twig', [
            'carDealer' => $carDealerRepository->findAll(),
            'carType' => $vehicleTypeRepository->findAll(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/services", name="services")
     */
    public function services()
    {
        return $this->render('default/services.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request, \Swift_Mailer $mailer, MailService $mailService)
    {
        if (!empty($request->request->get('object'))) {
            $object = $request->request->get('object');
            $firstNameContact = $request->request->get('firstNameContact');
            $lastNameContact = $request->request->get('lastNameContact');
            $emailContact = $request->request->get('emailContact');
            $messageContact = $request->request->get('message');
            $adrMail = 'zozotueur@gmail.com';
            $information = [
                $firstNameContact,
                $lastNameContact,
                $emailContact,
                $messageContact,
            ];
            $contentMail = $this->renderView('emails/registration.html.twig',
                ['information' => $information]);
            $contentMailContact = $this->renderView('emails/mailContact.html.twig',
                ['information' => $information]);
            $mailService->sendMail(
                $mailer,
                'Contact : '.$object,
                $adrMail,
                $adrMail, $contentMail);
            $mailService->sendMail(
                $mailer,
                'Information demande : '.$object,
                $adrMail, $emailContact, $contentMailContact);


            return $this->render('default/validationMail.html.twig');
        } else {
            return $this->render('default/contact.html.twig');
        }
    }
}
