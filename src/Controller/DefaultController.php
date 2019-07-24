<?php

namespace App\Controller;


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
            $messageAdmin = (new \Swift_Message('Contact : ' . $object))
                ->setFrom('zozotueur@gmail.com')
                ->setTo('zozotueur@gmail.com')
                ->setBody(
                    $this->renderView(
                    // templates/emails/registration.html.twig
                        'emails/registration.html.twig',
                        [
                            'firstNameContact' => $firstNameContact,
                            'lastNameContact' => $lastNameContact,
                            'emailContact' => $emailContact,
                            'messageContact' => $messageContact
                        ]
                    ),
                    'text/html'
                );

            $messageClient = (new \Swift_Message('Contact : ' . $object))
                ->setFrom('zozotueur@gmail.com')
                ->setTo($emailContact)
                ->setBody(
                    $this->renderView(
                    // templates/emails/registration.html.twig
                        'emails/mailContact.html.twig',
                        [
                            'firstNameContact' => $firstNameContact,
                            'lastNameContact' => $lastNameContact,
                            'emailContact' => $emailContact,
                            'messageContact' => $messageContact
                        ]
                    ),
                    'text/html'
                );

            $mailer->send($messageAdmin);
            $mailer->send($messageClient);
            return $this->render('default/validationMail.html.twig');
        } else {
            return $this->render('default/contact.html.twig');
        }
    }
}
