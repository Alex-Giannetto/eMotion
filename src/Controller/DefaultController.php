<?php

namespace App\Controller;


use App\Service\MailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
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
            $information = array();
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
/*
    public function sendMail(\Swift_Mailer $mailer, $subject, $from, $to, $view ,array $informationBody)
    {
        $message = (new \Swift_Message( $subject))
            ->setFrom($from)
            ->setTo($to)
            ->setBody(
                $this->renderView(
                // templates/emails/registration.html.twig
                    $view,
                    [
                        'information' => $informationBody
                    ]
                ),
                'text/html'
            );

        $mailer->send($message);
    }*/
}
