<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Comment;
use App\Security\TokenGenerator;


class UserRegisterSubscriber implements EventSubscriberInterface
{
    private $passwordEncoder;

    private $tokenGenerator;

    private $mailer;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder,TokenGenerator $tokenGenerator, \Swift_Mailer $mailer)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->tokenGenerator = $tokenGenerator;
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['hashPassword', EventPriorities::PRE_WRITE]
        ];
    }

    public function hashPassword(GetResponseForControllerResultEvent $event)
    {
        $user = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$user instanceof User ||
            !in_array($method, [Request::METHOD_POST])) {
            return;
        }

        $user->setPassword(
            $this->passwordEncoder->encodePassword($user, $user->getPassword())
        );
        $user->setConfirmationToken($this->tokenGenerator->getRandomSecureToken());
        
        // Sending Email Confirmation
        $message = (new \Swift_Message("Hello"))
            ->setFrom("imen.sliti100@gmail.com")
            ->setTo("farhatabbes.bendaoud@esprit.tn")
            ->setBody("Hi How are you"); 
        $this->mailer->send($message);
    }
}