<?php

namespace App\Controller;

use App\Entity\Proprietaire;
use App\Entity\Rdv;
use App\Entity\Secretaire;
use App\Entity\User;
use App\Entity\Veterinaire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {


        if ($this->isGranted($this->getParameter('ROLE_PROPRIETAIRE'))) {
            /** @var Proprietaire $proprietaire */
            $proprietaire = $this->getUser();
            $rdvs = $this->getDoctrine()
                         ->getRepository(Rdv::class)
                         ->findAVenirByProprietaire($proprietaire)
            ;

            return $this->render('proprietaire/home.html.twig', [
                'rdvs' => $rdvs,
            ]);
        } elseif ($this->isGranted($this->getParameter('ROLE_VETERINAIRE'))) {
            /** @var Veterinaire $veterinaire */
            $veterinaire = $this->getUser();

            return $this->redirectToRoute('clinique_show', [
                'id' => $veterinaire->getClinique()
                                    ->getId()
            ]);
        } elseif ($this->isGranted($this->getParameter('ROLE_CLINIQUE'))) {
            /** @var Secretaire $secretaire */
            $secretaire = $this->getUser();

            return $this->redirectToRoute('clinique_show', [
                'id' => $secretaire->getClinique()
                                    ->getId()
            ]);
        }

        return $this->render('index/index.html.twig');

    }


}
