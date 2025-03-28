<?php

namespace App\Controller;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

final class FeedController extends AbstractController
{
    #[Route('/feed', name: 'app_feed')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $posts = $entityManager->getRepository(Post::class)->findBy([], ['creationDate' => 'DESC']);

        return $this->render('feed/feed.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/post/create', name: 'post_create', methods: ['POST'])]
    public function createPost(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $title = $request->request->get('title');
        $content = $request->request->get('content');
        $file = $request->files->get('file');

        // Vérifier si le titre est bien renseigné
        if (empty($title)) {
            $this->addFlash('error', 'The title is required.');
            return $this->redirectToRoute('app_feed');
        }

        $post = new Post();
        $post->setTitle($title);
        $post->setContent($content);
        $post->setCreationDate(new \DateTime());
        $post->setLastModifiedDate(new \DateTime());

        // Gestion de l'upload d'image
        if ($file instanceof UploadedFile) {
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

            $file->move($this->getParameter('uploads_directory'), $newFilename);
            $post->setFileUrl('/uploads/' . $newFilename);
        }

        $entityManager->persist($post);
        $entityManager->flush();

        return $this->redirectToRoute('app_feed');
    }
}