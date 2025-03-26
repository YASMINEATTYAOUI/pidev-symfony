<?php

namespace App\Controller;

use App\Entity\Agent;
use App\Entity\Citizen;
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
    #[Route('/api/create-agent', name: 'api_create_agent', methods: ['POST'])]
    public function createAgent(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em): Response
    {
        $username = $request->request->get('username');
        $password = $request->request->get('password');
        $agentFullName = $request->request->get('agentFullName');
        $agentEmail = $request->request->get('agentEmail');
        $agentPhoneNumber = $request->request->get('agentPhoneNumber');
    
        if (!$username || !$password || !$agentFullName || !$agentEmail || !$agentPhoneNumber) {
            $this->addFlash('error', 'Please fill in all the fields');
            return $this->redirectToRoute('api_create_agent');
        }
    
        // Check if the email or username already exists
        $existingAgent = $em->getRepository(Agent::class)->findOneBy(['email' => $agentEmail]);
        $existingCitizen = $em->getRepository(Citizen::class)->findOneBy(['email' => $agentEmail]);
        $existingUser = $em->getRepository(User::class)->findOneBy(['username' => $username]);
    
        if ($existingAgent || $existingCitizen) {
            $this->addFlash('error', 'Email is already in use.');
            return $this->redirectToRoute('api_create_agent');
        }
    
        if ($existingUser) {
            $this->addFlash('error', 'Username is already in use.');
            return $this->redirectToRoute('api_create_agent');
        }
    
        // Create a new agent
        $agent = new Agent();
        $agent->setFullName($agentFullName);
        $agent->setEmail($agentEmail);
        $agent->setPhoneNumber($agentPhoneNumber);
        $agent->setCreationDate(new \DateTime());
        $agent->setActiveStatus(true);
    
        // Persist the agent entity
        $em->persist($agent);
        $em->flush();
    
        // Create a new user with hashed password
        $user = new User();
        $user->setUsername($username);
        $hashedPassword = $passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);
        $user->setAgent($agent);
    
        // Persist the user entity
        $em->persist($user);
        $em->flush();
    
        return new Response('Agent and User created', Response::HTTP_CREATED);
        }


    #[Route('/agents', name: 'agent_list')]
    public function listAgents(Request $request, AgentRepository $agentRepository): Response
    {
        $search = $request->query->get('search', '');
        $agents = $agentRepository->findBySearch($search);

        return $this->render('agents/list.html.twig', [
            'agents' => $agents,
            'search' => $search,
        ]);
    }

    #[Route('/api/update-agent/{id}', name: 'api_update_agent', methods: ['PUT'])]
    public function updateAgent(int $id, Request $request, AgentRepository $agentRepository, EntityManagerInterface $entityManager): Response
    {
       
        $agent = $agentRepository->find($id);

        if (!$agent) {
            throw $this->createNotFoundException('Agent not found');
        }

        $agent->setFullName($request->request->get('fullName', $agent->getFullName()));
        $agent->setEmail($request->request->get('email', $agent->getEmail()));
        $agent->setPhoneNumber($request->request->get('phoneNumber', $agent->getPhoneNumber()));
        $agent->setActiveStatus($request->request->getBoolean('activeStatus', $agent->getActiveStatus()));
        $entityManager->flush();

        return $this->redirectToRoute('agent_list');
    }

    #[Route('/agents/delete/{id}', name: 'agent_delete')]
    public function deleteAgent(int $id, AgentRepository $agentRepository, EntityManagerInterface $entityManager): Response
    {

        $agent = $agentRepository->find($id);

        if (!$agent) {
            throw $this->createNotFoundException('Agent not found');
        }

        $entityManager->remove($agent);
        $entityManager->flush();

        return $this->redirectToRoute('agent_list');
    }
}
