<?php

namespace Api\Service;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Class ApiLoginService
 *
 * Represents a service for API login functionality.
 */
class ApiLoginService extends ApiService {
    private UserRepository $userRepository;
    private UserPasswordHasherInterface $userPasswordHasherInterface;
    /**
     * ApiLoginService constructor.
     *
     * @param UserRepository $userRepository The user repository.
     */
    public function __construct(UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        $this->userRepository = $userRepository;
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
    }

    /**
     * Validates the provided arguments for API login.
     *
     * @param HeaderBag $args The arguments for API login.
     * @return bool Returns true if the arguments are valid, otherwise false.
     */
    public function validate(HeaderBag $args): bool
    {
        // Check if "email" and "password" keys are present in the $args array
        if (!$args->has("email") && !$args->has("password")) return false;
        // Check if a user exists with the specified email and password in the user repository
        if ($args->get("email") === null && $args->get("password") === null) return false;

        $user = $this->userRepository->findOneBy(["email" => $args->get("email")]);
        if ($user === null) return false;

        if (!$this->userPasswordHasherInterface->isPasswordValid($user, $args->get("password"))) return false;

        // If all checks pass, return true
        return true;
    }

    /**
     * Generates a JSON response for incorrect login data.
     *
     * @param HeaderBag $args The arguments for API login.
     * @return JsonResponse The JSON response for incorrect login data.
     */
    public function getWrongJsonResponse(HeaderBag $args): JsonResponse
    {
        return new JsonResponse("Incorrect data", Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Generates a JSON response for correct login data.
     *
     * @param HeaderBag $args The arguments for API login.
     * @return JsonResponse The JSON response for correct login data.
     */
    public function getCorrectJsonResponse(HeaderBag $args): JsonResponse
    {
        $user = $this->userRepository->findOneBy(["email" => $args->get("email")]);
        return new JsonResponse($user->toJson(), Response::HTTP_OK);
    }
}
