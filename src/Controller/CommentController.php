<?php
namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CommentController extends AbstractController
{
    #[Route('/comment/create/{postId}', name: 'comment_create', methods: ['POST'])]
    public function createComment(
        Request $request, 
        EntityManagerInterface $entityManager, 
        int $postId
    ): Response {
        // Récupérer le post
        $post = $entityManager->getRepository(Post::class)->find($postId);
        
        if (!$post) {
            $this->addFlash('error', 'Post not found.');
            return $this->redirectToRoute('app_feed');
        }

        // Vérifier le contenu du commentaire
        $content = $request->request->get('content');
        if (empty($content)) {
            $this->addFlash('error', 'Comment content is required.');
            return $this->redirectToRoute('app_feed');
        }

        // Créer le nouveau commentaire
        $comment = new Comment();
        $comment->setContent($content);
        $comment->setCreationDate(new \DateTime());
        $comment->setPost($post);
        
        // Dans une application réelle, vous devriez définir le citoyen connecté
        // $comment->setCitizen($this->getUser());

        // Persister le commentaire
        $entityManager->persist($comment);
        $entityManager->flush();

        return $this->redirectToRoute('app_feed');
    }
}