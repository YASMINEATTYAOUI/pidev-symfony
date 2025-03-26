<?php

namespace App\Controller;

use App\Entity\Citizen;
use App\Repository\CitizenRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CitizenController extends AbstractController
{
    #[Route('/citizens/toggle-status/{id}', name: 'citizen_toggle_status')]
    public function toggleStatus(int $id, EntityManagerInterface $entityManager): Response
    {
        $citizen = $entityManager->getRepository(Citizen::class)->find($id);

        if (!$citizen) {
            throw $this->createNotFoundException('Citizen not found');
        }

        $citizen->setActiveStatus(!$citizen->getActiveStatus());
        $entityManager->flush();

        $this->addFlash('success', 'Citizen status updated successfully.');
        return $this->redirectToRoute('citizen_list');
    }

    #[Route('/citizens', name: 'citizen_list')]
    public function listCitizens(Request $request, CitizenRepository $citizenRepository , UserRepository $userRepository): Response
    {
        $search = $request->query->get('search', '');
        $page = max(1, (int) $request->query->get('page', 1));
        $limit = 10; // Default items per page
        $offset = ($page - 1) * $limit;

        $citizens = $citizenRepository->findBySearch($search, $limit, $offset);


        foreach ($citizens as $citizen) {
            $user = $userRepository->findOneBy(['citizen' => $citizen->getId()]);
            $citizen->username = $user ? $user->getUsername() : null;
        }

        return $this->render('agents/list_citizens.html.twig', [
            'citizens' => $citizens,
            'search' => $search,
            'page' => $page,
        ]);
    }
}
