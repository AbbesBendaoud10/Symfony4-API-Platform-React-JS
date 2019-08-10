<?php


namespace App\Entity;

use Symfony\Component\Security\Core\User\UserInterface;


interface AuthorisedEntityInterface
{
    public function setAuthor(UserInterface $user): AuthorisedEntityInterface;
}