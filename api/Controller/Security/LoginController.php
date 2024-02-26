<?php

namespace Api\Controller\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\JWTService;
use Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class LoginController extends AbstractApiSecurityController{

    public JWTService $jwtService;

    public function __construct(JWTService $jwtService, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        parent::__construct($userRepository, $userPasswordHasherInterface);
        $this->jwtService = $jwtService;
    }

    #[Route("/api/login", name:"api_login", methods:"POST")]
    public function index(Request $request, Security $security): Response
    {
        if (!$this->isValidRequest($request->request)) return $this->getWrongResponse(null, $this->getWrongResponseMessage(), "");

        $email = $request->request->get("email");
        $password = $request->request->get("password");

        $user = (new User)->setEmail($email)->setPassword($password);
        $response = $this->getResponse($user, $security);

        if ($response->getStatusCode() != Response::HTTP_OK) return $response;

        $jwtToken = $this->jwtService->generateToken([
            ["email" => $email]
        ], "+60 minutes");

        $decodeToken = JWT::decode($jwtToken, "secure_coding");

        return new JsonResponse([
            "message" => $this->getCorrectResponseMessage(),
            "jwt_token" => $jwtToken,
            "decode_token" => $decodeToken
        ], Response::HTTP_OK);
    }

    public function isValidResponse(?User $user): bool
    {
        if (is_null($user)) return false;
        $userExist = $this->userRepository->findOneBy(["email" => $user->getEmail()]);
        return $userExist && $this->userPasswordHasherInterface->isPasswordValid($userExist, $user->getPassword());
    }

    public function isValidRequest(InputBag $body): bool
    {
        if (!$body->has("email") || !$body->has("password")) return false;
        return true;
    }

    public function getWrongResponse(?User $user, string $message, mixed $additionalObject): Response
    {
        return new JsonResponse($message, Response::HTTP_UNAUTHORIZED);
    }

    public function getCorrectResponse(?User $user, string $message, mixed $additionalObject): Response
    {
        return new JsonResponse($message, Response::HTTP_OK);
    }
}