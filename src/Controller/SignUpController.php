<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SignUpController extends AbstractController{
    #[Route('auth/sign_up', name: 'app_sign_up')]
    public function index(): Response
    {
        return $this->render('auth/sign_up.html.twig', [
            'controller_name' => 'SignUpController',
        ]);
    }
}
