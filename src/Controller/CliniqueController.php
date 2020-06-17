<?php

namespace App\Controller;

use App\Entity\Clinique;
use App\Form\CliniqueType;
use App\Repository\CliniqueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/clinique")
 */
class CliniqueController extends AbstractController
{
    /**
     * @Route("/", name="clinique_index", methods={"GET"})
     */
    public function index(CliniqueRepository $cliniqueRepository): Response
    {
        return $this->render('clinique/index.html.twig', [
            'cliniques' => $cliniqueRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="clinique_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $clinique = new Clinique();
        $form = $this->createForm(CliniqueType::class, $clinique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($clinique);
            $entityManager->flush();

            return $this->redirectToRoute('clinique_index');
        }

        return $this->render('clinique/new.html.twig', [
            'clinique' => $clinique,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="clinique_show", methods={"GET"})
     * @Security("is_granted('ROLE_CLINIQUE') or is_granted('ROLE_ADMIN')")
     */
    public function show(Clinique $clinique): Response
    {
        return $this->render('clinique/show.html.twig', [
            'clinique' => $clinique,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="clinique_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Clinique $clinique): Response
    {
        $form = $this->createForm(CliniqueType::class, $clinique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('clinique_index');
        }

        return $this->render('clinique/edit.html.twig', [
            'clinique' => $clinique,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="clinique_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Clinique $clinique): Response
    {
        if ($this->isCsrfTokenValid('delete'.$clinique->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($clinique);
            $entityManager->flush();
        }

        return $this->redirectToRoute('clinique_index');
    }
}
