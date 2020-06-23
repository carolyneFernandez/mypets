<?php

namespace App\Controller;

use App\Entity\Proprietaire;
use App\Form\ProprietaireType;
use App\Repository\ProprietaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Service\MailService;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

/**
 * @Route("/proprietaire")
 */
class ProprietaireController extends AbstractController
{
    /**
     * @Route("/", name="proprietaire_index", methods={"GET"})
     * @Security("is_granted('ROLE_CLINIQUE') or is_granted('ROLE_VETERINAIRE')")
     */
    public function index(ProprietaireRepository $proprietaireRepository): Response
    {
        return $this->render('proprietaire/index.html.twig', [
            'proprietaires' => $proprietaireRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="proprietaire_new", methods={"GET","POST"})
     * @Security("is_granted('ROLE_CLINIQUE') or is_granted('ROLE_VETERINAIRE')")
     * @param Request $request
     * @param MailService $mailService
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param TokenGeneratorInterface $tokenGenerator
     * @return Response
     * @throws \Exception
     */
    public function new(Request $request, MailService $mailService, UserPasswordEncoderInterface $passwordEncoder, TokenGeneratorInterface $tokenGenerator): Response
    {
        $proprietaire = new Proprietaire();
        $form = $this->createForm(ProprietaireType::class, $proprietaire);
        $form->handleRequest($request);
        $proprietaire->addRole($this->getParameter('ROLE_PROPRIETAIRE'));

        // password générer aléatoirement lors de la création d'un vétérinaire
        $randomPassword = random_bytes(10);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $proprietaire->setPassword($passwordEncoder->encodePassword($proprietaire, $randomPassword));
            $proprietaire->setToken($tokenGenerator->generateToken());

            $entityManager->persist($proprietaire);
            $entityManager->flush();

            $this->addFlash('success', 'Un mail de confirmation va être envoyé au propriétaire !');
            $mailService->setAndSendMail($proprietaire->getEmail(), 'Votre compte particuliers MyPets', 'mail/set_password.html.twig', ['user' => $proprietaire]);

            return $this->redirectToRoute('proprietaire_index');
        }

        return $this->render('proprietaire/new.html.twig', [
            'proprietaire' => $proprietaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="proprietaire_show", methods={"GET"})
     * @Security("is_granted('ROLE_CLINIQUE') or is_granted('ROLE_PROPRIETAIRE') or is_granted('ROLE_VETERINAIRE')")
     */
    public function show(Proprietaire $proprietaire): Response
    {
        return $this->render('proprietaire/show.html.twig', [
            'proprietaire' => $proprietaire,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="proprietaire_edit", methods={"GET","POST"})
     * @Security("is_granted('ROLE_CLINIQUE') or is_granted('ROLE_PROPRIETAIRE') or is_granted('ROLE_VETERINAIRE')")
     */
    public function edit(Request $request, Proprietaire $proprietaire): Response
    {
        $form = $this->createForm(ProprietaireType::class, $proprietaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('proprietaire_index');
        }

        return $this->render('proprietaire/edit.html.twig', [
            'proprietaire' => $proprietaire,
            'form' => $form->createView(),
        ]);
    }

}
