<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Doctrine\ORM\EntityManagerInterface;


class UserConfirmationSubscriber implements EventSubscriberInterface{
    
    private $userRepository;

    private $entityManager;
    
    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager; 
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['confirmUser', EventPriorities::POST_VALIDATE]
        ];
    }

    public function confirmUser(GetResponseForControllerResultEvent $event){
        $request = $event->getRequest();

        if($request->get("_route") !== "api_user_confirmations_post_collection"){
            return;
        }

        $confirmationToken = $event->getControllerResult();

        $user = $this->userRepository->findOneBy(
            ['confirmationToken' => $confirmationToken->confirmationToken]
        );

        if(!$user){
            throw new NotFoundHttpException();    
        }

        $user
            ->setConfirmationToken(null)
            ->setEnabled(true);
        $this->entityManager->flush();
        
        $event->setResponse(new JsonResponse(null, Response::HTTP_OK));
    }

}