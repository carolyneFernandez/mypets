<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Entity\User;
use App\Form\AnimalType;
use App\Repository\AnimalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use App\Service\MailService;
use App\Service\FileUploader;


/**
 * @Route("/animal")
 */
class AnimalController extends AbstractController
{
    /**
     * @Route("/", name="animal_index", methods={"GET"})
     * @Security("is_granted('ROLE_CLINIQUE') or is_granted('ROLE_VETERINAIRE') or is_granted('ROLE_PROPRIETAIRE')")
     * @param AnimalRepository $animalRepository
     * @return Response
     */
    public function index(AnimalRepository $animalRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if(in_array($this->getParameter('ROLE_VETERINAIRE'), $user->getRoles()) || in_array($this->getParameter('ROLE_PROPRIETAIRE'), $user->getRoles())){
            
            return $this->render('animal/index.html.twig', [
                'animals' => $user->getAnimals(),
            ]);

        } else {
            return $this->render('animal/index.html.twig', [
                'animals' => $animalRepository->findAll(),
            ]);
        }
    }

    /**
     * @Route("/new", name="animal_new", methods={"GET","POST"})
     * @Security("is_granted('ROLE_CLINIQUE') or is_granted('ROLE_VETERINAIRE') or is_granted('ROLE_PROPRIETAIRE')")
     */
    public function new(Request $request, FileUploader $fileUploader): Response
    {
        $animal = new Animal();
        $form = $this->createForm(AnimalType::class, $animal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $photo = $form->get('photo')->getData();

            if ($photo != null) {
                $dir = $this->getParameter('dir_avatar_animal');
                $filename = $fileUploader->upload($photo, $dir, true);
                if($filename){
                    $animal->setPhoto($filename);
                }
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($animal);
            $entityManager->flush();

            /**
             * Envoyer un mail au propriétaire quand il est créé ?
             */

            return $this->redirectToRoute('animal_index');
        }

        return $this->render('animal/new.html.twig', [
            'animal' => $animal,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="animal_show", methods={"GET"})
     * @Security("is_granted('ROLE_CLINIQUE') or is_granted('ROLE_VETERINAIRE') or is_granted('ROLE_PROPRIETAIRE')")
     * @param Animal $animal
     * @return Response
     */
    public function show(Animal $animal): Response
    {
        return $this->render('animal/show.html.twig', [
            'animal' => $animal,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="animal_edit", methods={"GET","POST"})
     * @Security("is_granted('ROLE_CLINIQUE') or is_granted('ROLE_VETERINAIRE') or is_granted('ROLE_PROPRIETAIRE')")
     * @param Request $request
     * @param Animal $animal
     * @return Response
     */
    public function edit(Request $request, Animal $animal): Response
    {
        $form = $this->createForm(AnimalType::class, $animal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('animal_index');
        }

        return $this->render('animal/edit.html.twig', [
            'animal' => $animal,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="animal_delete", methods={"DELETE"})
     * @param Request $request
     * @param Animal $animal
     * @return Response
     */
    public function delete(Request $request, Animal $animal): Response
    {
        if ($this->isCsrfTokenValid('delete'.$animal->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($animal);
            $entityManager->flush();
        }

        return $this->redirectToRoute('animal_index');
    }
}
