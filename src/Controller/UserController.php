<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserPasswordForgotType;
use App\Form\UserPasswordType;
use App\Service\MailService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class UserController extends AbstractController
{
    private $userPasswordEncoder;

    /**
     * UserController constructor.
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }


    /**
     * @Route("/user", name="user")
     */
    public function index()
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }


    /**
     * @Route("/set-password/{token}", name="user_set_password", methods={"GET", "POST"})
     * @param Request $request
     * @param $token
     * @return Response
     */
    public function setPassword(Request $request, $token): Response
    {
        $entityManager = $this->getDoctrine()
                              ->getManager()
        ;
        $user = $entityManager->getRepository(User::class)
                              ->findOneBy(['token' => $token])
        ;

        if (!$user) {
            $this->addFlash('danger', 'Ce token n\'est plus valide.');

            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(UserPasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $user->setPassword($this->userPasswordEncoder->encodePassword($user, $user->getPassword()));
                $user->setToken(null);
                $entityManager->flush();

                $this->addFlash('success', 'Votre mot de passe a bien été modifié');

                return $this->redirectToRoute('app_login');
            } catch (OptimisticLockException $e) {
            } catch (ORMException $e) {
            }
            $this->addFlash('danger', 'Une erreur s\'est produite, veuillez réessayer');

        }

        return $this->render('user/set_password.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/forgot-password", name="user_forgot_password", methods={"GET", "POST"})
     * @param Request $request
     * @param TokenGeneratorInterface $tokenGenerator
     * @param MailService $mailService
     * @return RedirectResponse|Response
     */
    public function forgotPassword(Request $request, TokenGeneratorInterface $tokenGenerator, MailService $mailService)
    {


        $form = $this->createForm(UserPasswordForgotType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')
                          ->getData()
            ;
            $entityManager = $this->getDoctrine()
                                  ->getManager()
            ;

            /** @var User $user */
            $user = $entityManager->getRepository(User::class)
                                  ->findOneBy(['email' => $email])
            ;

            if ($user) {

                $user->setToken($tokenGenerator->generateToken());
                $entityManager->flush();

                $mailService->setAndSendMail($user->getEmail(), 'Mot de passe oublié', 'mail/forgot_password.html.twig', ['user' => $user]);
                $this->addFlash('success', 'Vous allez recevoir un mail de réinitilisation de mot de passe.');

                return $this->redirectToRoute('app_login');


            } else {
                $this->addFlash('danger', 'L\'email saisie ne correspond à aucun compte connu.');
            }

        }

        return $this->render('user/forgot_password.html.twig', [
            'form' => $form->createView()
        ]);


    }


}
