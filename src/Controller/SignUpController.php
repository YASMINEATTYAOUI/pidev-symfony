<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Agent;
use App\Entity\User;
use App\Entity\Citizen;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
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



    #[Route('/api/signup', name: 'api_signup')]
    public function signup(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em): Response
    {

        if ($request->isMethod('POST')) {
            // Get all form data
            $username = $request->request->get('username');
            $password = $request->request->get('password');
            $confirmPassword = $request->request->get('confirm-password');
            $fullName = $request->request->get('fullName');
            $email = $request->request->get('email');
            $cin = $request->request->get('cin');
            $phoneNumber = $request->request->get('phoneNumber');
            $latitude = $request->request->get('latitude');
            $longitude = $request->request->get('longitude');
            $profilePicture = $request->files->get('profilePicture');
    
            // Basic validation
if (!$username || !$password || !$confirmPassword || !$fullName || !$email || !$cin || !$phoneNumber) {
    $this->addFlash('error', 'Please fill in all the required fields');
    return $this->redirectToRoute('api_signup');
}
            // Password confirmation check
if ($password !== $confirmPassword) {
    $this->addFlash('error', 'Passwords do not match');
    return $this->redirectToRoute('api_signup');
}
            // CIN validation (8 digits starting with 0 or 1)
if (!preg_match('/^[01]\d{7}$/', $cin)) {
    $this->addFlash('error', 'CIN must be 8 digits starting with 0 or 1');
    return $this->redirectToRoute('api_signup');
}
            // Phone number validation (8 digits)
if (!preg_match('/^\d{8}$/', $phoneNumber)) {
    $this->addFlash('error', 'Phone number must be 8 digits');
    return $this->redirectToRoute('api_signup');
}
            // Check if the email or username already exists
            $existingUser = $em->getRepository(User::class)->findOneBy(['username' => $username]);
            $existingCitizen = $em->getRepository(Citizen::class)->findOneBy(['email' => $email]);
            $existingAgent = $em->getRepository(Agent::class)->findOneBy(['email' => $email]);
            if ($existingUser) {
                $this->addFlash('error', 'Username is already in use');
                return $this->redirectToRoute('api_signup');
            }
    
            if ($existingAgent) {
                $this->addFlash('error', 'Email is already in use');
                return $this->redirectToRoute('api_signup');
            }
            if ($existingCitizen) {
                $this->addFlash('error', 'Email is already in use');
                return $this->redirectToRoute('api_signup');
            }
$imagePath = 'default.png';
if ($profilePicture) {
    $newFilename = uniqid('profile_', true) . '.' . $profilePicture->guessExtension();

    try {
        $profilePicture->move(
            "/profile_pictures",
            $newFilename
        );
        $imagePath = $newFilename;
    } catch (FileException $e) {
        $this->addFlash('error', 'Failed to upload profile picture: ' . $e->getMessage());
        return $this->redirectToRoute('api_signup');
    }
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
            $citizen->setActiveStatus(true);
            $citizen->setLevel(0);
            $citizen->setScore(0);
            $citizen->setImagePath($imagePath);
            
            // Set location if provided

            if ($latitude && $longitude) {
            $adress = new Address() ;
            $adress->setLatitude($latitude);
            $adress->setLongitude($longitude); 
            $citizen->setAddress($adress);
            $em->persist($adress);
            }
    
            // Associate the citizen with the user
            $user->setCitizen($citizen);
    
            // Persist the user and citizen entities
            $em->persist($user);
            $em->persist($citizen);
            $em->flush();
            
            $this->addFlash('success', 'Registration successful! You can now login');
            return $this->redirectToRoute('app_login');
        }
    
        return $this->render('auth/sign_up.html.twig');
    }
    




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
