<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ResetType;
use App\Form\EditAvatarType;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
	/**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }

    /**
     * @Route("/profile", name="app_profile")
     * @Security("is_granted('ROLE_USER')")
     */
    public function profile()
    {   
        $user = $this->getUser();
        return $this->render('user/profile.html.twig', [ 'user' => $user]);
    }
 
    /**
     * @Route("/avatar_edit/{id}", name="app_avatar_edit")
     * @Entity("user", expr="repository.find(id)")
     * @Security("is_granted('ROLE_USER')")
     */
    public function avatarEdit( ObjectManager $manager, User $user, Request $request, FileUploader $fileUploader)
    {
        $form = $this->createForm(EditAvatarType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { 
                if ($avatarFile = $form['avatar']->getData()) {                    
                    $avatarFileName = $fileUploader->upload($avatarFile);
                    $user->setAvatarFilename($avatarFileName);
                    $manager->flush();                    
                }
            return $this->redirectToRoute('app_profile');
        }
        
        return $this->render('user/profile_edit.html.twig', [ 'user' => $user, 'form' => $form->createView()]);
    }

    /**
     * @Route("/avatar_delete/{id}", name="app_avatar_delete")
     * @Entity("user", expr="repository.find(id)")
     * @Security("is_granted('ROLE_USER')")
     */
    public function avatarDelete(ObjectManager $manager, User $user)
    {
        $user->setAvatarFilename(null);
        $manager->flush();
        return $this->render('user/profile.html.twig', [ 'user' => $user ]);
    }
}