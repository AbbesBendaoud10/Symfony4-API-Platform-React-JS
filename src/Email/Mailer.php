<?php

namespace App\Email;

class Mailer{

    private $mailer;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function senConfirmationEmail(User $user){
        
        $body = $this->twig->render('email/confirmation.html.twig',[
            'user' => $user
        ]);         
        $message = (new \Swift_Message("Hello"))
        ->setFrom("imen.sliti100@gmail.com")
        ->setTo($user->getEmail())
        ->setBody($body); 
    
        $this->mailer->send($message);
    }

}