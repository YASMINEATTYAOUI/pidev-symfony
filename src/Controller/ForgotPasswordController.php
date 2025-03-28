<?php
namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Agent;
use App\Entity\Citizen;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ForgotPasswordController extends AbstractController
{
    #[Route('/forgot-password', name: 'app_forgot_password')]
    public function request(Request $request, TokenGeneratorInterface $tokenGenerator, EntityManagerInterface $em, MailerInterface $mailer): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
    
            // Check if the email belongs to a Citizen
            $citizen = $em->getRepository(Citizen::class)->findOneBy(['email' => $email]);
            // Check if the email belongs to an Agent
            $agent = $em->getRepository(Agent::class)->findOneBy(['email' => $email]);
    
            if (!$citizen  && !$agent) {
                $this->addFlash('error', 'No account found for this email.');
                return $this->redirectToRoute('app_forgot_password');
            }
    
            $user = null;
            if ($citizen) {
                $user = $em->getRepository(User::class)->findOneBy(['citizen' => $citizen]);
            } elseif ($agent) {
                $user = $em->getRepository(User::class)->findOneBy(['agent' => $agent]);
            }
            $token = $tokenGenerator->generateToken();
            $user->setResetToken($token);
            $em->persist($user);
            $em->flush();
    
            $resetUrl = 'http://localhost:8000/reset-password/'. $token;
    
            $emailMessage = (new Email())
                ->from('fedibenmansour7@gmail.com')
                ->to($email)
                ->subject('Password Reset Request')
                ->html("Click <a href='$resetUrl'>here</a> to reset your password.");
    
    try{        $mailer->send($emailMessage);
        $this->addFlash('success', 'A password reset link has been sent to your email.');

    }catch (Exception $e) {
            // Log the error message for debugging purposes
            error_log('Email sending failed: ' . $e->getMessage());
            $this->addFlash('error', 'Failed to send the password reset email. Please try again later.');
        }
            return $this->redirectToRoute('app_login');
        }
    
        return $this->render('auth/forgot_password.html.twig');
    }



    #[Route('/reset-password/{token}', name: 'app_reset_password')]
    public function reset(Request $request, string $token, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em): Response
    {
        $user = $em->getRepository(User::class)->findOneBy(['resetToken' => $token]);

        if (!$user) {
            $this->addFlash('error', 'Invalid or expired reset token.');
            return $this->redirectToRoute('app_forgot_password');
        }

        if ($request->isMethod('POST')) {
            $password = $request->request->get('password');

            $hashedPassword = $passwordHasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);
            $user->setResetToken(null);

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Your password has been reset successfully.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('auth/reset_password.html.twig', ['token' => $token]);
    }
}

