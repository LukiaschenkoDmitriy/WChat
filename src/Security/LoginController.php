<?php

namespace App\Security;

use App\Entity\User;
use App\Form\LoginType;
use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller for handling user login.
 */
class LoginController extends AbstractLoginController {

    private HubInterface $hubInterface;

    public function __construct(
        UserRepository $userRepository,
        UserPasswordHasherInterface $userPasswordHasherInterface,
        HubInterface $hubInterface
    )
    {
        parent::__construct($userRepository, $userPasswordHasherInterface);
        $this->hubInterface = $hubInterface;
    }

    /**
     * Handles user login.
     * 
     * @param Request $request The HTTP request object.
     * @param Security $security The Symfony security component.
     * @return Response The response object.
     */
    #[Route('/login', name: 'security_login')]
    public function index(Request $request, Security $security): Response
    {
        // Get the result of form submission
        $formResult = $this->getFormResult($request, LoginType::class, $security);  

        $response = $formResult->getResponse();
        // If there is a response, return it
        if ($response != null) {
            return $formResult->getResponse();
        }

        $response = $this->render("security/login.html.twig", [
            "form" => $formResult->getForm()->createView()
        ]);

        // Render the login form
        return $response;
    }

    /**
     * Checks if the user response is valid.
     * 
     * @param User|null $user The user object.
     * @return bool True if the response is valid, otherwise false.
     */
    public function isValidResponse(?User $user): bool
    {
        if (is_null($user)) return false;
        $userExist = $this->userRepository->findOneBy(["email" => $user->getEmail()]);
        return $userExist && $this->userPasswordHasherInterface->isPasswordValid($userExist, $user->getPassword());
    }

    /**
     * Checks if the additional object is valid.
     * 
     * @param mixed $object The additional object.
     * @return bool True if the additional object is valid, otherwise false.
     */
    public function isValidAdditionalObject(mixed $object): bool
    {
        return ($object instanceof FormInterface);
    }

    /**
     * Handles wrong response.
     * 
     * @param User|null $user The user object.
     * @param string $message The error message.
     * @param mixed $additionalObject The additional object.
     * @return Response The response object.
     */
    public function getWrongResponse(?User $user, string $message, mixed $additionalObject): Response
    {
        return ($this->render("security/login.html.twig", [
            "form" => $additionalObject->createView(),
            "error" => $message
        ])->setStatusCode(Response::HTTP_UNAUTHORIZED));
    }

    /**
     * Handles correct response.
     * 
     * @param User|null $user The user object.
     * @param string $message The success message.
     * @param mixed $additionalObject The additional object.
     * @return Response The response object.
     */
    public function getCorrectResponse(?User $user, string $message, mixed $additionalObject): Response
    {
        // Body Response = "jwt" => $additionalObject
        return ($this->redirectToRoute("app_react", [
            "jwt_token" => $additionalObject
        ])->setStatusCode(Response::HTTP_OK));
    }
}
