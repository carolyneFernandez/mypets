<?php

namespace App\Controller;

use App\Entity\AnimalType;
use App\Form\AnimalTypeType;
use App\Repository\AnimalTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


/**
 * @Route("/animalType")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class AnimalTypeController extends AbstractController
{
    /**
     * @Route("/", name="animal_type_index", methods={"GET"})
     */
    public function index(AnimalTypeRepository $animalTypeRepository): Response
    {
        return $this->render('animal_type/index.html.twig', [
            'animal_types' => $animalTypeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="animal_type_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $animalType = new AnimalType();
        $form = $this->createForm(AnimalTypeType::class, $animalType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($animalType);
            $entityManager->flush();

            return $this->redirectToRoute('animal_type_index');
        }

        return $this->render('animal_type/new.html.twig', [
            'animal_type' => $animalType,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="animal_type_show", methods={"GET"})
     */
    public function show(AnimalType $animalType): Response
    {
        return $this->render('animal_type/show.html.twig', [
            'animal_type' => $animalType,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="animal_type_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, AnimalType $animalType): Response
    {
        $form = $this->createForm(AnimalTypeType::class, $animalType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('animal_type_index');
        }

        return $this->render('animal_type/edit.html.twig', [
            'animal_type' => $animalType,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="animal_type_delete", methods={"DELETE"})
     */
    public function delete(Request $request, AnimalType $animalType): Response
    {
        if ($this->isCsrfTokenValid('delete'.$animalType->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($animalType);
            $entityManager->flush();
        }

        return $this->redirectToRoute('animal_type_index');
    }
}
