<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\BlogPost;
use App\Repository\UserRepository;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;


class AuhtorAddSubscriber implements EventSubscriberInterface
{
    private  $user;

    private  $tokenStorage;

    public function __construct(UserRepository $user, TokenStorageInterface $tokenStorage)
    {
        $this->user = $user;
        $this->tokenStorage = $tokenStorage;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['authorAdd', EventPriorities::PRE_WRITE]
        ];
    }

    public function authorAdd(GetResponseForControllerResultEvent $event)
    {
        $blogPost = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$blogPost instanceof BlogPost || Request::METHOD_POST !== $method){
            return;
        }
        $author = $this->tokenStorage->getToken()->getUser();
        $blogPost->setAuthor($this->user->find(8));
    }
}