<?php

namespace App\Controller;

use App\Entity\Consultation;
use App\Entity\User;
use App\Form\ConsultationType;
use App\Repository\ConsultationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/consultation")
 */
class ConsultationController extends AbstractController
{
    /**
     * @Route("/", name="consultation_index", methods={"GET"})
     * @Security("is_granted('ROLE_VETERINAIRE') or is_granted('ROLE_PROPRIETAIRE') or is_granted('ROLE_SECRETAIRE')")
     */
    public function index(ConsultationRepository $consultationRepository): Response
    {
        return $this->render('consultation/index.html.twig', [
            'consultations' => $consultationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="consultation_new", methods={"GET","POST"})
     * @Security("is_granted('ROLE_VETERINAIRE')")
     */
    public function new(Request $request): Response
    {
        $consultation = new Consultation();
        $form = $this->createForm(ConsultationType::class, $consultation);
        $form->handleRequest($request);
        /** @var User $user */
        $user = $this->getUser();
        $consultation->setVeterinaire($user);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($consultation);
            $entityManager->flush();

            return $this->redirectToRoute('rdv_index');
        }

        return $this->render('consultation/new.html.twig', [
            'consultation' => $consultation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="consultation_show", methods={"GET"})
     * @Security("is_granted('ROLE_VETERINAIRE') or is_granted('ROLE_PROPRIETAIRE') or is_granted('ROLE_SECRETAIRE')")
     */
    public function show(Consultation $consultation): Response
    {
        return $this->render('consultation/show.html.twig', [
            'consultation' => $consultation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="consultation_edit", methods={"GET","POST"})
     * @Security("is_granted('ROLE_VETERINAIRE')")
     */
    public function edit(Request $request, Consultation $consultation): Response
    {   
        $today = new \DateTime('today');

        if($consultation->getDateCreation() != $today){
            $this->addFlash('warning', "Vous pouvez modifier votre consultation seulement le même jour de sa création.");
        }

        $form = $this->createForm(ConsultationType::class, $consultation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('consultation_index');
        }

        return $this->render('consultation/edit.html.twig', [
            'consultation' => $consultation,
            'form' => $form->createView(),
        ]);
    }

}
