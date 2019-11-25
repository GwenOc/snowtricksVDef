<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use App\Form\ResetType;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use App\Service\MailService;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route("/account")
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, ObjectManager $manager,UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer, FileUploader $fileUploader )
    {
        $user = new User();

        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $avatarFile = $form['avatar']->getData();
                if ($avatarFile) {
                    $avatarFileName = $fileUploader->upload($avatarFile);
                    $user->setavatarFilename($avatarFileName);
                }

            $hash = $passwordEncoder->encodePassword($user, $user->getPassword());

            $user->setPassword($hash);
            $manager->persist($user);
            $manager->flush();        

            $token = $user->getToken();
            $url = $this->generateUrl('app_activate', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

             $message = (new \Swift_Message('Forgot Password'))
                    ->setSubject('Bienvenue sur snowtricks')
                    ->setFrom('send@example.com')
                    ->setTo($user->getEmail())
                    ->setBody(
                        $this->renderView('mail/activate.html.twig', [ 'user' => $user, 'url' => $url]), 'text/html'
            );
            $mailer->send($message);
            
            return $this->redirectToRoute('app_home_index');
        }

        return $this->render('security/register.html.twig', [
            'formRegister' => $form->createView()]);
    }

    /**
     * @Route("/activate/{token}", name="app_activate")
     */
    public function activate(Request $request, ObjectManager $manager, UserRepository $repo, string $token)
    {
        $user = $repo->findOneByToken($token);

        if (null === $user) {
            return $this->redirectToRoute('app_home_index');
        }

        $user->setRoles(['ROLE_USER']);
        $manager->flush();

        // $this->addFlash('notice','Compte activé');
        return $this->redirectToRoute('app_login');
    }

    /**
     * @Route("/forgottenPassword", name="app_forgotten_password")
     */
    public function forgottenPassword(Request $request, \Swift_Mailer $mailer,ObjectManager $manager, UserRepository $repo)
    {
        if (!$request->isMethod('POST')) {
            return $this->render('security/forgotten_password.html.twig');
        } 

         
        $email = $request->get('email');
        $user = $repo->findOneByEmail($email);

        if (null === $user) {
            $this->addFlash('Email incorrect');

            return $this->redirectToRoute('app_home_index');
        }

        $token = $user->getToken();        
        $manager->flush();

        $url = $this->generateUrl('app_reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

        $message = (new \Swift_Message('Forgot Password'))
            ->setSubject('Mot de passe oublié')
            ->setFrom('send@example.com')
            ->setTo($user->getEmail())
            ->setBody( 
                 $this->renderView('mail/forgot.html.twig', [ 'user' => $user, 'url' => $url]), 'text/html'
            );


        $mailer->send($message);

        return $this->redirectToRoute('app_home_index');
    }

    /**
     * @Route("/resetPassword/{token}", name="app_reset_password")
     */
    public function resetPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder, ObjectManager $manager, UserRepository $repo, string $token)
    {

        $user = $repo->findOneByToken($token);
        $form = $this->createForm(ResetType::class, $user);

            if (null !== $user) {

                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $hash = $passwordEncoder->encodePassword($user, $user->getPassword());
                    $user->setToken(null)
                         ->setPassword($hash);
                    $manager->flush();

                    $this->addFlash('notice','Mot de passe modifié');
                    return $this->redirectToRoute('app_login');
                }
            }           
                    
        
        return $this->render('security/reset_password.html.twig', [
                            'form' => $form->createView(),]); 
    }
    

}
