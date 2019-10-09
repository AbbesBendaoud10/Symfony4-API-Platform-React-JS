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
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Entity\Comment;
use App\Entity\AuthorisedEntityInterface;


class AuhtorAddSubscriber implements EventSubscriberInterface
{
    private  $user;

    private  $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
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
        $entity = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        
        $author = $this->tokenStorage->getToken()->getUser();
        
        if ((!$entity instanceof AuthorisedEntityInterface) || Request::METHOD_POST !== $method){
            return;
        }
        
        $entity->setAuthor($author);
    }
}