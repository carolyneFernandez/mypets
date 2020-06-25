<?php

namespace App\Controller;

use App\Entity\Clinique;
use App\Entity\Secretaire;
use App\Form\CliniqueType;
use App\Repository\CliniqueRepository;
use App\Service\MailService;
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

//    /**
//     * @Route("/new", name="admin_clinique_new", methods={"GET","POST"})
//     * @param Request $request
//     * @return Response
//     */
//    public function new(Request $request): Response
//    {
//        $clinique = new Clinique();
//        $form = $this->createForm(CliniqueType::class, $clinique);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $entityManager = $this->getDoctrine()
//                                  ->getManager()
//            ;
//            $entityManager->persist($clinique);
//            $entityManager->flush();
//
//            return $this->redirectToRoute('admin_clinique_index');
//        }
//
//        return $this->render('clinique/new.html.twig', [
//            'clinique' => $clinique,
//            'form' => $form->createView(),
//        ]);
//    }

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


    /**
     * @Route("/{id}/valide", name="admin_clinique_valider", methods={"GET"})
     * @param Request $request
     * @param Clinique $clinique
     * @param MailService $mailService
     * @return Response
     */
    public function valide(Request $request, Clinique $clinique, MailService $mailService): Response
    {

        if ($clinique->getDemande()) {

            /** @var Secretaire $secretaire */
            $secretaire = $clinique->getSecretaires()
                                   ->first()
            ;
            $clinique->setDemande(false);
            $secretaire->setActif(true);
            $this->getDoctrine()
                 ->getManager()
                 ->flush()
            ;

            $mailService->setAndSendMail($secretaire->getEmail(), 'Validation de votre inscription', 'mail/registration/valide.html.twig', ['clinique' => $clinique]);

            $this->addFlash('success', 'L\'inscription de la clinique a été validée.');

        } else {
            $this->addFlash('danger', 'L\'inscription de la clinique a déjà été validée.');
        }


        return $this->redirectToRoute('clinique_show', [
            'id' => $clinique->getId(),
        ]);
    }

    /**
     * @Route("/{id}/refuse", name="admin_clinique_refuser", methods={"GET","POST"})
     * @param Request $request
     * @param Clinique $clinique
     * @param MailService $mailService
     * @return Response
     */
    public function refuser(Request $request, Clinique $clinique, MailService $mailService): Response
    {

        if ($clinique->getDemande()) {

            $motifRefus = $request->request->get('motifRefus');

            if ($motifRefus != null) {
                /** @var Secretaire $secretaire */
                $secretaire = $clinique->getSecretaires()
                                       ->first()
                ;
                $mailService->setAndSendMail($secretaire->getEmail(), 'Refus de votre inscription', 'mail/registration/refus.html.twig', [
                    'clinique' => $clinique,
                    'motifRefus' => $motifRefus
                ]);

                $em = $this->getDoctrine()
                           ->getManager()
                ;

                $em->remove($secretaire);
                $em->remove($clinique);
                $em->flush();
                $this->addFlash('warning', 'L\'inscription a été refusée.');

            } else {
                $this->addFlash('danger', 'Vous devez saisir un motif de refus d\'inscription.');
            }

        } else {
            $this->addFlash('danger', 'L\'inscription de la clinique a déjà été validée.');
        }


        return $this->redirectToRoute('clinique_index');
    }


}
