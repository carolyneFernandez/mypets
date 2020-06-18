<?php

namespace App\Controller;

use App\Entity\Veterinaire;
use App\Form\VeterinaireType;
use App\Repository\VeterinaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


/**
 * @Route("/veterinaire")
 */
class VeterinaireController extends AbstractController
{
    /**
     * @Route("/", name="veterinaire_index", methods={"GET"})
     */
    public function index(VeterinaireRepository $veterinaireRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if(in_array($this->getParameter('ROLE_CLINIQUE'), $user->getRoles())){
            return $this->render('veterinaire/index.html.twig', [
                'veterinaires' => $user->getClinique()->getVeterinaires(),
            ]);
        }

        return $this->render('veterinaire/index.html.twig', [
            'veterinaires' => $veterinaireRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="veterinaire_new", methods={"GET","POST"})
     * @Security("is_granted('ROLE_CLINIQUE') or is_granted('ROLE_ADMIN')")
     */
    public function new(Request $request): Response
    {
        $veterinaire = new Veterinaire();
        $form = $this->createForm(VeterinaireType::class, $veterinaire);
        $form->handleRequest($request);
        
        /** @var User $user */
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            
            if(in_array($this->getParameter('ROLE_CLINIQUE'), $user->getRoles())){
                $veterinaire->setClinique($user->getClinique());
            }
            
            $entityManager->persist($veterinaire);
            $entityManager->flush();
            return $this->redirectToRoute('veterinaire_index');
        }

        return $this->render('veterinaire/new.html.twig', [
            'veterinaire' => $veterinaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="veterinaire_show", methods={"GET"})
     * @Security("is_granted('ROLE_CLINIQUE') or is_granted('ROLE_ADMIN')")
     */
    public function show(Veterinaire $veterinaire): Response
    {
        return $this->render('veterinaire/show.html.twig', [
            'veterinaire' => $veterinaire,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="veterinaire_edit", methods={"GET","POST"})
     * @Security("is_granted('ROLE_CLINIQUE') or is_granted('ROLE_ADMIN')")
     */
    public function edit(Request $request, Veterinaire $veterinaire): Response
    {
        $form = $this->createForm(VeterinaireType::class, $veterinaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('veterinaire_index');
        }

        return $this->render('veterinaire/edit.html.twig', [
            'veterinaire' => $veterinaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="veterinaire_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Veterinaire $veterinaire): Response
    {
        if ($this->isCsrfTokenValid('delete'.$veterinaire->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($veterinaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('veterinaire_index');
    }
}
