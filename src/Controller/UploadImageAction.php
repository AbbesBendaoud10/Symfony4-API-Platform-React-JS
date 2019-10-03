<?php

namespace App\Controller;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use Symfony\Component\Validator\Exception\ValidatorException;
use ApiPlatform\Core\Validator\Exception\ValidationException;
use App\Form\ImageType;
use App\Entity\Image;


class UploadImageAction{

    private $formFactory;

    private $entityManager;

    private $validator;

    public function __construct(FormFactoryInterface $formFactory, EntityManagerInterface $entityManager,  ValidatorInterface $validator)
    {
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    public function __invoke(Request $request)
    {
        //Create a new Image
        $image =new Image();

        //Validate Form
        $form = $this->formFactory->create(ImageType::class, $image);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->entityManager->persist($image);
            $this->entityManager->flush();
            
            $image->setFile(null);

            return $image;
        }

        throw new ValidationException(
            $this->validator->validate($image)
        );

    }
}