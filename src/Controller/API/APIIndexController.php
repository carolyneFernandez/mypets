<?php


namespace App\Controller\API;


use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\User;
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

}