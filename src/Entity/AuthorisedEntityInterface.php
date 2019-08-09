<?php


namespace App\Entity;

use Symfony\Component\Security\Core\User\UserInterface;


interface AuthorisedEntityInterface
{
    public function setPublised(UserInterface $user): AuthorisedEntityInterface;
}