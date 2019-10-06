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
use App\Security\UserConfirmationService;


class UserConfirmationSubscriber implements EventSubscriberInterface{
    

    private $userConfirmationService;
    
    public function __construct(UserConfirmationService $userConfirmationService)
    {
        $this->userConfirmationService = $userConfirmationService;
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

        //Confirmation Token using Service
        $this->userConfirmationService->confirmUser($confirmationToken->confirmationToken);
        
        $event->setResponse(new JsonResponse(null, Response::HTTP_OK));
    }

}