<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Entity\Clinique;
use App\Repository\CliniqueRepository;



/**
 * @Route("/clinique_proximite")
 */
class MapController extends AbstractController
{
    
    /**
     * @Route("/", name="clinique_proximite", methods={"GET"})
     */
    public function index(): Response
    {
      return $this->render('clinique_proximite/clinique_proximite.html.twig');
    }

    /**
     * @Route("/api/getCliniqueProximite", methods={"GET"})
     * @param CliniqueRepository $cliniqueRepository
     */
    public function cliniqueLocalisation(CliniqueRepository $cliniqueRepository): JsonResponse
    {
      $allCLinique = $cliniqueRepository->findAll();
      return new JsonResponse($allCLinique);
        // //URL l'api externe
        // $url = "https://public.opendatasoft.com/api/records/1.0/search/?dataset=medecins&q=clinique%2C+Ile-de-France&timezone=Europe%2FParis";
        // $response = json_decode(file_get_contents($url));
        // return new JsonResponse($response);
    }

    /**
     * @Route("/api/getUserLocalisation", methods={"GET"})
     * @param Request $request
     */
    public function getUserLocalisation(Request $request): JsonResponse
    {

      $url = "ipv4.icanhazip.com"; 
  
      // Initialize a CURL session. 
      $ch = curl_init();  
  
      // Return Page contents. 
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
      //grab URL and pass it to the variable. 
      curl_setopt($ch, CURLOPT_URL, $url); 
      $curlResponse = curl_exec($ch);
      
      $ip = str_replace("\n","", $curlResponse);

      $URLcurrentUserInfo = 'http://ip-api.com/json/'. $ip;

      $response = json_decode(file_get_contents($URLcurrentUserInfo));
      return new JsonResponse($response);
    }
 }
