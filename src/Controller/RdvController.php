<?php

namespace App\Controller;

use App\Entity\Proprietaire;
use App\Entity\Rdv;
use App\Form\RdvStep2Type;
use App\Form\RdvStep3Type;
use App\Form\RdvType;
use App\Repository\RdvRepository;
use App\Service\MailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/rdv")
 */
class RdvController extends AbstractController
{
    /**
     * @Route("/", name="rdv_index", methods={"GET"})
     * @param RdvRepository $rdvRepository
     * @return Response
     */
    public function index(RdvRepository $rdvRepository): Response
    {
        $rdv = [];

        if ($this->isGranted($this->getParameter('ROLE_PROPRIETAIRE'))) {
            $rdv = $this->getDoctrine()
                        ->getRepository(Rdv::class)
                        ->findBy([
                            'completed' => true,
                            'proprietaire' => $this->getUser()
                        ], ['date' => 'DESC'])
            ;
        } elseif ($this->isGranted($this->getParameter('ROLE_CLINIQUE'))) {
            $rdv = $this->getDoctrine()
                        ->getRepository(Rdv::class)
                        ->findByClinique($this->getUser()
                                              ->getClinique())
            ;
        }

        return $this->render('rdv/index.html.twig', [
            'rdvs' => $rdv,
        ]);
    }

    /**
     *
     * @Route("/new/{rdv}", name="rdv_new", methods={"GET","POST"}, defaults={"rdv"=null})
     * @param Request $request
     * @param Rdv $rdv
     * @return Response
     */
    public function new(Request $request, Rdv $rdv = null): Response
    {
        $entityManager = $this->getDoctrine()
                              ->getManager()
        ;

        if ($rdv == null) {

            $rdv = $entityManager->getRepository(Rdv::class)
                                 ->findOneBy([
                                     'author' => $this->getUser(),
                                     'completed' => false
                                 ])
            ;

            if (!$rdv) {
                $rdv = new Rdv();
                $rdv->setAuthor($this->getUser());
            } else {
                $this->addFlash('info', 'Vous êtes sur un rendez-vous déjà en cours de saisie. Vous pouvez annuler sa saisie et en créer un nouveau en cliquant <a href="' . $this->generateUrl('rdv_annule_and_new', ['id' => $rdv->getId()]) . '">ici</a> ');
            }
        }

        if ($this->isGranted($this->getParameter('ROLE_PROPRIETAIRE'))) {
            /** @var Proprietaire $proprietaire */
            $proprietaire = $this->getUser();
            $rdv->setProprietaire($proprietaire);
        }

        $form = $this->createForm(RdvType::class, $rdv);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($rdv);
            $entityManager->flush();

            return $this->redirectToRoute('rdv_new_step2', [
                'rdv' => $rdv->getId(),
            ]);
        }

        return $this->render('rdv/new.html.twig', [
            'rdv' => $rdv,
            'form' => $form->createView(),
        ]);
    }

    /**
     *
     * @Route("/new-step2/{rdv}", name="rdv_new_step2", methods={"GET","POST"})
     * @param Request $request
     * @param Rdv $rdv
     * @return Response
     */
    public function newChoiceStep2(Request $request, Rdv $rdv): Response
    {

        $entityManager = $this->getDoctrine()
                              ->getManager()
        ;


        $form = $this->createForm(RdvStep2Type::class, $rdv);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('rdv_new_step3', [
                'rdv' => $rdv->getId(),
            ]);
        }

        return $this->render('rdv/new_step2.html.twig', [
            'rdv' => $rdv,
            'form' => $form->createView(),
        ]);
    }

    /**
     *
     * @Route("/new-step3/{rdv}", name="rdv_new_step3", methods={"GET","POST"})
     * @param Request $request
     * @param Rdv $rdv
     * @param MailService $mailService
     * @return Response
     */
    public function newChoiceStep3(Request $request, Rdv $rdv, MailService $mailService): Response
    {

        $entityManager = $this->getDoctrine()
                              ->getManager()
        ;


        $form = $this->createForm(RdvStep3Type::class, $rdv);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rdv->setCompleted(true);

            if ($this->isGranted($this->getParameter('ROLE_CLINIQUE'))) {
                $rdv->setValide(true);
                $mailService->setAndSendMail($rdv->getProprietaire()
                                                 ->getEmail(), 'Votre demande de rendez-vous', 'mail/rdv/valide.html.twig', ['rdv' => $rdv]);
            } elseif ($this->isGranted('ROLE_PROPRIETAIRE')) {
                $mailService->setAndSendMail($rdv->getProprietaire()
                                                 ->getEmail(), 'Votre demande de rendez-vous', 'mail/rdv/received.html.twig', ['rdv' => $rdv]);
            }

            $entityManager->flush();

            return $this->redirectToRoute('rdv_index', []);
        }

        return $this->render('rdv/new_step3.html.twig', [
            'rdv' => $rdv,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="rdv_show", methods={"GET"})
     */
    public function show(Rdv $rdv): Response
    {
        return $this->render('rdv/show.html.twig', [
            'rdv' => $rdv,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="rdv_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Rdv $rdv): Response
    {
        $form = $this->createForm(RdvType::class, $rdv);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()
                 ->getManager()
                 ->flush()
            ;

            return $this->redirectToRoute('rdv_index');
        }

        return $this->render('rdv/edit.html.twig', [
            'rdv' => $rdv,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="rdv_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Rdv $rdv): Response
    {
        if ($this->isCsrfTokenValid('delete' . $rdv->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()
                                  ->getManager()
            ;
            $entityManager->remove($rdv);
            $entityManager->flush();
        }

        return $this->redirectToRoute('rdv_index');
    }


    /**
     * @Route("/annule/{id}", name="rdv_annule_and_new", methods={"GET"})
     * @param Rdv $rdv
     * @return RedirectResponse
     */
    public function annuleRdvAndNew(Rdv $rdv)
    {
        if ($rdv->getAuthor() != $this->getUser() || $rdv->getCompleted()) {
            $this->addFlash('danger', 'Accès interdit.');

            return $this->redirectToRoute('rdv_index');
        }

        $em = $this->getDoctrine()
                   ->getManager()
        ;

        $em->remove($rdv);
        $em->flush();

        return $this->redirectToRoute('rdv_new');

    }

}
