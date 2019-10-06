<?php

namespace App\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserAdminController extends BaseAdminController{
    
    private $encoderPassword;
    
    public function __construct(UserPasswordEncoderInterface $encoderPassword){
        $this->encoderPassword = $encoderPassword;
    }

    protected function persistEntity($entity){
        $entity->setPassword($this->encoderPassword->encodePassword($entity, $entity->getPassword()));
        parent::persistEntity($entity);
    }

    protected function updateEntity($entity){
        parent::updateEntity($entity);
    }
}