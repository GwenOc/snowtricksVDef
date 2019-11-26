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
     * @Route("/trick/new_trick", name="new_trick" )
     */
    public function new(Request $request, ObjectManager $manager, FileUploader $fileUploader)
    {
        $user = $this->getUser();
        $trick = new Trick();
        $form = $this->createForm(CreateTrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($pictureFiles = $form['images']->getData()) {
                foreach ($pictureFiles as $pictureFile) {
                    $newFilename = $fileUploader->upload($pictureFile['file']);
                    $picture = new Picture();
                    $picture
                        ->setName($newFilename)
                        ->setTrick($trick)
                    ;

                    $trick->addPicture($picture);

                    $manager->persist($picture);
                }
            }

            $trick->setAuthor($user);

            $manager->persist($trick);
            $manager->flush();
            $this->addFlash('success', 'Le trick a bien été ajouté');

            return $this->redirectToRoute('trick_view', ['id' => $trick->getId()]);
        }

        return $this->render('trick/new_trick.html.twig', [
            'form'  => $form->createView()
        ]);
    }

    /**
     * @Route("/trick/delete/{id}", name="trick_delete")
     * @Entity("trick", expr="repository.find(id)")
     * @Security("is_granted('ROLE_USER')")
     */
    public function delete(Trick $trick, ObjectManager $manager)
    {
        $manager->remove($trick);
        $manager->flush();
        $this->addFlash('success', 'trick effacé avec succès');

        return $this->redirectToRoute('app_home_index');
    }

    /**
     * @Route("/trick/edit/{id}", name="trick_edit")
     * @Entity("trick", expr="repository.find(id)")
     * @Security("is_granted('ROLE_USER')")
     */
    public function edit(Trick $trick, ObjectManager $manager, Request $request, FileUploader $fileUploader )
    {
        $form = $this->createForm(CreateTrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($pictureFiles = $form['images']->getData()) {
                foreach ($pictureFiles as $pictureFile) {
                    $newFilename = $fileUploader->upload($pictureFile['file']);
                    $picture = new Picture();
                    $picture
                        ->setName($newFilename)
                        ->setTrick($trick)
                    ;

                    $trick->addPicture($picture);

                    $manager->persist($picture);
                }
            }

            $manager->flush();
            $this->addFlash('success', 'Le trick a bien été édité');

            return $this->redirectToRoute('trick_view', ['id' => $trick->getId()]);
        }


        return $this->render('trick/trick_edit.html.twig',[
            'trick' => $trick,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/picture/delete/{id}", name="picture_delete")
     * @Entity("picture", expr="repository.find(id)")
     * @Security("is_granted('ROLE_USER')")
     */
    public function deletePicture(Picture $picture, ObjectManager $manager)
    {
        $trick = $picture->getTrick();
        $manager->remove($picture);
        $manager->flush();

        return $this->redirectToRoute('trick_edit', ['id' => $trick->getId()]);

    }

    /**
     * @route("/video/delete/{id}/{key}", name="video_delete")
     * @Entity("trick", expr="repository.find(id)")
     * @Security("is_granted('ROLE_USER')")
     */
    public function videoDelete(ObjectManager $manager, int $key, Trick $trick)
    {
        $videoLinks = $trick->getVideoLinks();
        if(array_key_exists($key, $videoLinks)) {
            unset($videoLinks[$key]);
            $trick->setVideolinks($videoLinks);
            $manager->flush();
        }

        return $this->redirectToRoute('trick_edit', ['id' => $trick->getId()]);

    }

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
