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
 * @Security("is_granted('ROLE_ADMIN')")
 * @Route("/admin/clinique")
 */
class CliniqueAdminController extends AbstractController
{
    /**
     * @Route("/", name="admin_clinique_index", methods={"GET"})
     * @param CliniqueRepository $cliniqueRepository
     * @return Response
     */
    public function index(CliniqueRepository $cliniqueRepository): Response
    {
        return $this->render('clinique/index.html.twig', [
            'cliniques' => $cliniqueRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_clinique_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $clinique = new Clinique();
        $form = $this->createForm(CliniqueType::class, $clinique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()
                                  ->getManager()
            ;
            $entityManager->persist($clinique);
            $entityManager->flush();

            return $this->redirectToRoute('admin_clinique_index');
        }

        return $this->render('clinique/new.html.twig', [
            'clinique' => $clinique,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_clinique_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Clinique $clinique
     * @return Response
     */
    public function edit(Request $request, Clinique $clinique): Response
    {
        $form = $this->createForm(CliniqueType::class, $clinique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()
                 ->getManager()
                 ->flush()
            ;

            return $this->redirectToRoute('admin_clinique_index');
        }

        return $this->render('clinique/edit.html.twig', [
            'clinique' => $clinique,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_clinique_delete", methods={"DELETE"})
     * @param Request $request
     * @param Clinique $clinique
     * @return Response
     */
    public function delete(Request $request, Clinique $clinique): Response
    {
        if ($this->isCsrfTokenValid('delete' . $clinique->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()
                                  ->getManager()
            ;
            $entityManager->remove($clinique);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_clinique_index');
    }

}
