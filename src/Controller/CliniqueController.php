<?php

namespace App\Controller;

use App\Entity\Clinique;
use App\Form\CliniqueType;
use App\Repository\CliniqueRepository;
use http\Client\Curl\User;
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
     * @Security("is_granted('ROLE_CLINIQUE') or is_granted('ROLE_ADMIN')")
     */
    public function edit(Request $request, Clinique $clinique): Response
    {
        $form = $this->createForm(CliniqueType::class, $clinique);
        $form->handleRequest($request);
        /** @var User $user */
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            if(in_array($this->getParameter('ROLE_CLINIQUE'), $user->getRoles())){
                return $this->redirectToRoute('clinique_show', ['id' => $clinique->getId()]);
            }

            return $this->redirectToRoute('clinique_index');
        }

        return $this->render('clinique/edit.html.twig', [
            'clinique' => $clinique,
            'form' => $form->createView(),
        ]);
    }

}
