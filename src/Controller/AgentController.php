<?php

namespace App\Controller;

use App\Entity\Agent;
use App\Entity\User;
use App\Repository\AgentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AgentController extends AbstractController
{
    #[Route('/api/create-agent', name: 'create_agent')]
    public function createAgent(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('GET')) {
            return $this->render('agents/create.html.twig');
        }

        if ($request->isMethod('POST')) {
            $username = $request->request->get('username');
            $password = $request->request->get('password');
            $agentFullName = $request->request->get('agentFullName');
            $agentEmail = $request->request->get('agentEmail');
            $agentPhoneNumber = $request->request->get('agentPhoneNumber');

            if (!$username || !$password || !$agentFullName || !$agentEmail || !$agentPhoneNumber) {
                $this->addFlash('error', 'Please fill in all the fields');
                return $this->redirectToRoute('create_agent');
            }

            $existingAgent = $em->getRepository(Agent::class)->findOneBy(['email' => $agentEmail]);
            $existingUser = $em->getRepository(User::class)->findOneBy(['username' => $username]);

            if ($existingAgent || $existingUser) {
                $this->addFlash('error', 'Email or username is already in use.');
                return $this->redirectToRoute('create_agent');
            }

            $agent = new Agent();
            $agent->setFullName($agentFullName);
            $agent->setEmail($agentEmail);
            $agent->setPhoneNumber($agentPhoneNumber);
            $agent->setCreationDate(new \DateTime());
            $agent->setActiveStatus(true);

            $em->persist($agent);
            $em->flush();

            $user = new User();
            $user->setUsername($username);
            $hashedPassword = $passwordHasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);
            $user->setAgent($agent);

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Agent and User created successfully.');
            return $this->redirectToRoute('agent_list');
        }

        throw $this->createNotFoundException('Method not allowed');
    }


    #[Route('/api/update-agent/{id}', name: 'api_update_agent', methods: ['GET', 'PUT'])]
    public function updateAgent(int $id, Request $request, AgentRepository $agentRepository, EntityManagerInterface $entityManager)
    {
       
        $agent = $agentRepository->find($id);

        if (!$agent) {
            throw $this->createNotFoundException('Agent not found');
        }

        if ($request->isMethod('GET')) {
            return $this->render('agents/update.html.twig', [
                'agent' => $agent,
            ]);
        }

        if ($request->isMethod('PUT')) {
            $agent->setFullName($request->request->get('fullName', $agent->getFullName()));
            $agent->setEmail($request->request->get('email', $agent->getEmail()));
            $agent->setPhoneNumber($request->request->get('phoneNumber', $agent->getPhoneNumber()));
            $agent->setActiveStatus($request->request->getBoolean('activeStatus', $agent->getActiveStatus()));
            $entityManager->flush();

            $this->addFlash('success', 'Agent updated successfully.');
            return $this->redirectToRoute('agent_list');
        }
    }

    #[Route('/agents', name: 'agent_list')]
    public function listAgents(Request $request, AgentRepository $agentRepository): Response
    {
        $search = $request->query->get('search', '');
        $page = max(1, (int) $request->query->get('page', 1));
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $agents = $agentRepository->findBySearch($search, $limit, $offset);

        return $this->render('agents/list.html.twig', [
            'agents' => $agents,
            'search' => $search,
            'currentPage' => $page,
            'totalPages' => ceil(count($agents) / $limit),
        ]);
    }

    #[Route('/agents/toggle-status/{id}', name: 'agent_toggle_status')]
    public function toggleStatus(int $id, AgentRepository $agentRepository, EntityManagerInterface $entityManager): Response
    {
        $agent = $agentRepository->find($id);

        if (!$agent) {
            throw $this->createNotFoundException('Agent not found');
        }

        $agent->setActiveStatus(!$agent->getActiveStatus());
        $entityManager->flush();

        $this->addFlash('success', 'Agent status updated successfully.');
        return $this->redirectToRoute('agent_list');
    }
}
