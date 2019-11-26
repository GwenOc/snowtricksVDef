<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/comment")
 */
class CommentController extends AbstractController
{	
	/**
     * @Route("/delete/{id}", name="comment_delete")
     * @Entity("comment", expr="repository.find(id)")
     * @Security ("(is_granted('ROLE_USER') and user === comment.getAuthor())")
     */
	public function deleteComment(Comment $comment, ObjectManager $manager)
	{	
		$manager->remove($comment);
        $manager->flush();
        $this->addFlash('success', 'commentaire effacé avec succès');			
		
        return $this->redirectToRoute('home');
	}

    /**
     * @Route("/seemore", name="seeMore", methods="GET")
     * @param Request $req
     * @return JsonResponse
     */
    public function seeMore(Request $req, CommentRepository $commentRepository): jsonresponse
    {
        $offset = $req->get('offset');
        $id = $req->get('id');
        $comments = $commentRepository->findMoreComments($offset, $id);
        $response = [
            'html' => $this->renderView('comment/seeMore.html.twig', ['comments' => $comments])
        ];
        return $this->json(
            json_encode($response)
        );
    }
}