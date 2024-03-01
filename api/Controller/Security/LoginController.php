<?php

namespace Api\Controller\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\JWTService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller for handling user login via API.
 */
class LoginController extends AbstractApiSecurityController
{
    /**
     * JWTService instance for generating JWT tokens.
     */
    public JWTService $jwtService;

    /**
     * Constructs a new LoginController instance.
     *
     * @param JWTService $jwtService The JWTService instance.
     * @param UserRepository $userRepository The user repository.
     * @param UserPasswordHasherInterface $userPasswordHasherInterface The password hasher interface.
     */
    public function __construct(JWTService $jwtService, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        parent::__construct($userRepository, $userPasswordHasherInterface);
        $this->jwtService = $jwtService;
    }

    /**
     * Handles the user login request.
     *
     * @param Request $request The HTTP request object.
     * @param Security $security The Symfony security component.
     * @return Response The HTTP response object.
     */
    #[Route("/api/login", name:"api_login", methods:"POST")]
    public function index(Request $request, Security $security): Response
    {
        // if the request is not valid, return a wrong response
        if (!$this->isValidRequest($request->request)) return $this->getWrongResponse(null, $this->getWrongResponseMessage(), "");

        // get email and password from request
        $email = $request->request->get("email");
        $password = $request->request->get("password");

        // create a user object with email and password
        $user = (new User)->setEmail($email)->setPassword($password);

        // get response based on user authentication
        $response = $this->getResponse($user, $security);

        // if authentication fails, return the response
        if ($response->getStatusCode() != Response::HTTP_OK) return $response;

        // generate JWT token
        $jwtToken = $this->jwtService->getToken($email, "+2 minutes");

        // return successful response with JWT token and decoded token
        return new JsonResponse([
            "message" => $this->getCorrectResponseMessage(),
            "jwt_token" => $jwtToken
        ], Response::HTTP_OK);
    }

    /**
     * Checks if the user response is valid.
     *
     * @param User|null $user The user object.
     * @return bool True if the user response is valid, otherwise false.
     */
    public function isValidResponse(?User $user): bool
    {
        // check if user exists and password is valid
        if (is_null($user)) return false;
        $userExist = $this->userRepository->findOneBy(["email" => $user->getEmail()]);
        return $userExist && $this->userPasswordHasherInterface->isPasswordValid($userExist, $user->getPassword());
    }

    /**
     * Checks if the request is valid.
     *
     * @param InputBag $body The request body.
     * @return bool True if the request is valid, otherwise false.
     */
    public function isValidRequest(InputBag $body): bool
    {
        // check if request body has email and password
        if (!$body->has("email") || !$body->has("password")) return false;
        return true;
    }

    /**
     * Gets the response for an invalid request.
     *
     * @param User|null $user The user object.
     * @param string $message The error message.
     * @param mixed $additionalObject Additional data.
     * @return Response The HTTP response object.
     */
    public function getWrongResponse(?User $user, string $message, mixed $additionalObject): Response
    {
        // return unauthorized response
        return new JsonResponse($message, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Gets the response for a correct request.
     *
     * @param User|null $user The user object.
     * @param string $message The success message.
     * @param mixed $additionalObject Additional data.
     * @return Response The HTTP response object.
     */
    public function getCorrectResponse(?User $user, string $message, mixed $additionalObject): Response
    {
        // return successful response
        return new JsonResponse($message, Response::HTTP_OK);
    }
}
