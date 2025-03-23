<?php

namespace App\Controller;

use App\Entity\Agent;
use App\Entity\User;
use App\Entity\Citizen;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
final class SignUpController extends AbstractController{
    #[Route('auth/sign_up', name: 'app_sign_up')]
    public function index(): Response
    {
        return $this->render('auth/sign_up.html.twig', [
            'controller_name' => 'SignUpController',
        ]);
    }



    #[Route('/api/signup', name: 'api_signup', methods: ['POST'])]
    public function signup(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em): Response
    {
        // Check if the request method is POST
        if ($request->isMethod('POST')) {
            // Access form data
            $username = $request->request->get('username');
            $password = $request->request->get('password');
            $fullName = $request->request->get('fullName');
            $email = $request->request->get('email');
            $cin = $request->request->get('cin');
            $phoneNumber = $request->request->get('phoneNumber');

            // Ensure the data contains the necessary fields
            if (!$username || !$password || !$fullName || !$email || !$cin || !$phoneNumber) {
                return new Response('Invalid input', Response::HTTP_BAD_REQUEST);
            }

            // Create a new user with hashed password
            $user = new User();
            $user->setUsername($username);
            $hashedPassword = $passwordHasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);

            // Create a new citizen
            $citizen = new Citizen();
            $citizen->setFullName($fullName);
            $citizen->setEmail($email);
            $citizen->setCin($cin);
            $citizen->setPhoneNumber($phoneNumber);
            $citizen->setCreationDate(new \DateTime());
            $citizen->setLastModifiedDate(new \DateTime());
            $citizen->setActiveStatus(true); // Assuming new citizens are active by default
            $citizen->setLevel(0);
            $citizen->setScore(0);
            $citizen->setImagePath("not now");

            // Associate the citizen with the user
            $user->setCitizen($citizen);

            // Persist the user and citizen entities
            $em->persist($user);
            $em->persist($citizen);
            $em->flush();

            // Redirect to login page after successful signup
            return new RedirectResponse('/auth/login', Response::HTTP_SEE_OTHER);
        }

        // Render the sign-up form if the request method is GET
        return $this->render('auth/sign_up.html.twig', [
            'controller_name' => 'SignUpController',
        ]);
    }
    




    #[Route('/api/create-agent', name: 'api_create_agent', methods: ['POST'])]
    public function createAgent(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em): Response
    {
        // Access form data
        $username = $request->request->get('username');
        $password = $request->request->get('password');
        $agentFullName = $request->request->get('agentFullName');
        $agentEmail = $request->request->get('agentEmail');
        $agentPhoneNumber = $request->request->get('agentPhoneNumber');

        // Ensure the data contains the necessary fields
        if (!$username || !$password || !$agentFullName || !$agentEmail || !$agentPhoneNumber) {
            return new Response('Invalid input', Response::HTTP_BAD_REQUEST);
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







    #[Route('/api/update-agent/{id}', name: 'api_update_agent', methods: ['PUT'])]
    public function updateAgent(int $id, Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em): Response
    {
        // Find the agent by ID
        $agent = $em->getRepository(Agent::class)->find($id);
        if (!$agent) {
            return new Response('Agent not found', Response::HTTP_NOT_FOUND);
        }

        // Access form data
        $agentFullName = $request->request->get('agentFullName');
        $agentEmail = $request->request->get('agentEmail');
        $agentPhoneNumber = $request->request->get('agentPhoneNumber');
        $username = $request->request->get('username');
        $password = $request->request->get('password');

        // Update agent details
        if ($agentFullName) {
            $agent->setFullName($agentFullName);
        }
        if ($agentEmail) {
            $agent->setEmail($agentEmail);
        }
        if ($agentPhoneNumber) {
            $agent->setPhoneNumber($agentPhoneNumber);
        }

        // Persist the agent entity
        $em->persist($agent);
        $em->flush();

        // Update associated user details
        $user = $agent->getUser(); // Assuming a one-to-one relationship
        if ($username) {
            $user->setUsername($username);
        }
        if ($password) {
            $hashedPassword = $passwordHasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);
        }

        // Persist the user entity
        $em->persist($user);
        $em->flush();

        return new Response('Agent and User updated', Response::HTTP_OK);
    }





    #[Route('/api/delete-agent/{id}', name: 'api_delete_agent', methods: ['DELETE'])]
    public function deleteAgent(int $id, EntityManagerInterface $em): Response
    {
        // Find the agent by ID
        $agent = $em->getRepository(Agent::class)->find($id);
        if (!$agent) {
            return new Response('Agent not found', Response::HTTP_NOT_FOUND);
        }

        // Find the associated user
        $user = $agent->getUser(); // Assuming a one-to-one relationship

        // Remove the agent entity
        $em->remove($agent);
        $em->flush();

        // Remove the associated user entity
        if ($user) {
            $em->remove($user);
            $em->flush();
        }

        return new Response('Agent and User deleted', Response::HTTP_OK);
    }
}
?>