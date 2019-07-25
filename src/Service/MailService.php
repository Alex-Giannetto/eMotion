<?php

namespace App\Service;

use Swift_Mailer;

class MailService
{
    public function sendMail(Swift_Mailer $mailer, $subject, $from, $to, $body)
    {
        $message = (new \Swift_Message($subject))
            ->setFrom($from)
            ->setTo($to)
            ->setBody($body,'text/html');

        $mailer->send($message);
    }
}
