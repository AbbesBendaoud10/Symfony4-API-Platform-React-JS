<?php


namespace App\Entity;

use Symfony\Component\Security\Core\User\UserInterface;


interface PublishedDateInterface
{
    public function setPublished(\DateTimeInterface $user): PublishedDateInterface;
}