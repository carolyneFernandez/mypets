<?php

namespace App\Controller;

use App\Entity\Clinique;
use App\Entity\Secretaire;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\AppCustomAuthenticator;
use App\Service\MailService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param GuardAuthenticatorHandler $guardHandler
     * @param AppCustomAuthenticator $authenticator
     * @return Response
     * @throws Exception
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, AppCustomAuthenticator $authenticator, MailService $mailService): Response
    {
        $clinique = new Clinique();
        $clinique->setDemande(true);

        $secretaire = new Secretaire();
        $secretaire->setActif(false);
        $clinique->addSecretaire($secretaire);
        $form = $this->createForm(RegistrationFormType::class, $clinique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $password = $form->get('secretaires')[0]['plainPassword']->getData();
            $secretaire->setPassword($passwordEncoder->encodePassword($secretaire, $password));

            $entityManager = $this->getDoctrine()
                                  ->getManager()
            ;
            $entityManager->persist($clinique);
            $entityManager->flush();
            // do anything else you need here, like send an email
            $this->addFlash('success', 'Votre demande d\'inscription a bien été prise en compte. Vous aurez un retour sous quelques heures.');

            $mailService->setAndSendMail($secretaire->getEmail(), 'Votre demande a bien été prise en compte', 'mail/registration/received.html.twig', ['clinique' => $clinique]);
            $mailService->setAndSendMail($this->getParameter('MAIL_ADMIN'), 'Nouvelle demande d\'inscription', 'mail/registration/new.html.twig', ['clinique' => $clinique]);

            return $this->redirectToRoute('index');
//            return $guardHandler->authenticateUserAndHandleSuccess(
//                $user,
//                $request,
//                $authenticator,
//                'main' // firewall name in security.yaml
//            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

}
