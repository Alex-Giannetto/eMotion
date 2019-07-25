<?php

namespace App\Controller;

use App\Service\MailService;
use App\Repository\CarDealerRepository;
use App\Repository\VehicleTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(CarDealerRepository $carDealerRepository, VehicleTypeRepository $vehicleTypeRepository)
    {
        return $this->render('default/index.html.twig', [
            'carDealer' => $carDealerRepository->findAll(),
            'carType' => $vehicleTypeRepository->findAll(),
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
    public function contact(Request $request, \Swift_Mailer $mailer)
    {
        if (!empty($request->request->get('object'))) {
            $object = $request->request->get('object');
            $firstNameContact = $request->request->get('firstNameContact');
            $lastNameContact = $request->request->get('lastNameContact');
            $emailContact = $request->request->get('emailContact');
            $messageContact = $request->request->get('message');
            $adrMail = 'zozotueur@gmail.com';
            $information = [$firstNameContact,$lastNameContact,$emailContact,$messageContact];
            $contentMail = $this->renderView('emails/registration.html.twig',[ 'information' => $information]);
            $contentMailContact = $this->renderView('emails/mailContact.html.twig',[ 'information' => $information]);
            $emailService = new MailService();
            $emailService->sendMail($mailer, 'Contact : '.$object,$adrMail,$adrMail,$contentMail);
            $emailService->sendMail($mailer, 'Information demande : '.$object,$adrMail,$emailContact,$contentMailContact);
            return $this->render('default/validationMail.html.twig');
        } else {
            return $this->render('default/contact.html.twig');
        }
    }
}
