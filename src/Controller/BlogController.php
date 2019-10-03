<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Security\UserConfirmationService;
use Symfony\Component\HttpFoundation\Response;

class BlogController extends AbstractController
{
    private $userConfirmationService;

    public function __construct(UserConfirmationService $userConfirmationService)
    {
        $this->userConfirmationService = $userConfirmationService;
    }

    /**
     * @Route("/blog", name="blog")
     */
    public function index()
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/BlogController.php',
        ]);
    }
    /**
     * @Route("/confirm/{token}", name="confirm_user")
     */
    public function confirmUser(string $token){
        $this->userConfirmationService->confirmUser($token);
        return $this->redirectToRoute('blog_index');
    }

        /**
     * @Route("/index", name="blog_index")
     */
    public function blogIndex()
    {
        return $this->render('index.html.twig');
    }
}
