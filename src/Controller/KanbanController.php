<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class KanbanController extends AbstractController{
    #[Route('/kanban', name: 'app_kanban')]
    public function index(): Response
    {
        return $this->render('reports/kanban.html.twig', [
            'controller_name' => 'KanbanController',
        ]);
    }
}
