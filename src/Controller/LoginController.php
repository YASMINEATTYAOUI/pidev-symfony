<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LoginController extends AbstractController{
    #[Route('/auth/login', name: 'app_login')]
    public function index(): Response
    {
        return $this->render('auth/login.html.twig', [
            'controller_name' => 'LoginController',
        ]);
    }
}
