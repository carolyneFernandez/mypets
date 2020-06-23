<?php

namespace App\Controller;

use App\Entity\Proprietaire;
use App\Entity\Rdv;
use App\Entity\User;
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
        } else {
            return $this->render('index/index.html.twig');

        }

    }


}
