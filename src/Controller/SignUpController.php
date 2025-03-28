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
    

            $em->persist($user);
            $em->persist($citizen);
            $em->flush();
            
            $this->addFlash('success', 'Registration successful! You can now login');
            return $this->redirectToRoute('app_login');
        }
    
        return $this->render('auth/sign_up.html.twig');
    }
    









}
