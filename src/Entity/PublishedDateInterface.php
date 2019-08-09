<?php


namespace App\Entity;

use Symfony\Component\Security\Core\User\UserInterface;


interface PublishedDateInterface
{
    public function setAuthor(\DateTimeInterface $user): PublishedDateInterface;
}