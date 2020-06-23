<?php


namespace App\Controller\API;


use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Animal;
use App\Entity\Proprietaire;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

/**
 * Class APIIndexController
 * @package App\Controller\API
 */
class APIIndexController extends AbstractController
{

    /** @var UserPasswordEncoderInterface */
    private $passwordEncoder;

    /** @var TokenGeneratorInterface */
    private $tokenGenerator;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, TokenGeneratorInterface $tokenGenerator)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->tokenGenerator = $tokenGenerator;
    }

    /**
     * @Route(
     *     name="api_login",
     *     path="/api/login",
     *     methods={"POST"},
     * )
     * @param Request $request
     * @return JsonResponse
     */
    public function apilogin(Request $request)
    {
        $data = json_decode($request->getContent());
        $email = $data->email;
        $password = $data->password;
        $em = $this->getDoctrine()
                   ->getManager()
        ;

        /** @var User $user */
        $user = $em->getRepository(User::class)
                   ->findOneBy(['email' => $email])
        ;

        if ($user) {

            if (!$user->getActif()) {
                return new JsonResponse('Votre compte n\'est pas actif', 403);
            }

            if ($this->passwordEncoder->isPasswordValid($user, $password)) {
                $token = $this->tokenGenerator->generateToken();
                $user->setApiToken($token);
                $em->flush();

                return new JsonResponse([
                    'data' => 'Succès',
                    'token' => $token
                ], 200);
            }

        }

        return new JsonResponse('Identifiants invalides', 401);

    }


    /**
     * @Route(
     *     name="api_animal_get_by_proprietaire",
     *     path="/api/animaux/get-by-proprietaire/{proprietaire}",
     *     methods={"GET"},
     *     options={"expose"=true}
     * )
     * @param Request $request
     * @param Proprietaire $proprietaire
     * @return JsonResponse
     * @Security("is_granted('ROLE_CLINIQUE') or is_granted('ROLE_PROPRIETAIRE')")
     */
    public function api_animal_get_by_proprietaire(Request $request, Proprietaire $proprietaire)
    {
        $animaux = $this->getDoctrine()
                        ->getRepository(Animal::class)
                        ->findBy([
                            'proprietaire' => $proprietaire,
                            'decede' => false
                        ])
        ;

        $choices = [];

        foreach ($animaux as $animal) {
            $choice['value'] = $animal->getId();
            $choice['label'] = '<img src=\'/' . $this->getParameter('dir_avatar_animal') . $animal->getPhoto() . '\' alt=\'photo\' height="100px" class="mr-2 rounded-pill">' . $animal->getNom();
            $choices[] = $choice;
        }

        return new JsonResponse(['animauxChoices' => $choices], 200);

    }


}