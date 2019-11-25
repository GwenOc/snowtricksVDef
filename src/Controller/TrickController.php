<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Picture;
use App\Entity\Trick;
use App\Form\CommentType;
use App\Form\CreateTrickType;
use App\Service\FileUploader;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class TrickController extends AbstractController
{
	/**
	* @Route("/trick/{id}", name="trick_view" ) 
	*/
	public function TrickView(Trick $trick, Request $request, ObjectManager $manager)
	    {
	        $user = $this->getUser();//On retourne l'utilisateur connecté
	        
	        $comment = new Comment();//On crée un nouveau commentaire

	        $form = $this->createForm(CommentType::class, $comment); //creation du form
	        
	        $form->handleRequest($request);//On récupère le sac de données

	        if ($form->isSubmitted() && $form->isValid()) {

	            $comment->setTrick($trick)
	                    ->setAuthor($user);
	            $manager->persist($comment);
	            $manager->flush();

	            return $this->redirectToRoute('trick_view',['id' => $trick->getId()]);
	        }  

	        return $this->render('trick/trick_view.html.twig',[
	                     'trick' => $trick,
	                     'commentForm' => $form->createView()
	                    ]);
	    }
}
