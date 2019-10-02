<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\UserCheckerInterface;
use App\Entity\User;
use Symfony\Component\Security\Core\Exception\DisabledException;



class UserEnabledChecker implements UserCheckerInterface{

    public function checkPreAuth(\Symfony\Component\Security\Core\User\UserInterface $user)
    {
        if(!$user instanceof User){
            return;
        }

        if(!$user->getEnabled()){
            throw new DisabledException("tameres");
        }
    }

    public function checkPostAuth(\Symfony\Component\Security\Core\User\UserInterface $user)
    {
        
    }

}