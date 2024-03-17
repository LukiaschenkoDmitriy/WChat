<?php

namespace App\Security;

use App\Entity\User;
use App\Form\RegisterType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller for handling user registration.
 */
class RegisterController extends AbstractRegisterController {
    /**
     * Handles user registration.
     * 
     * @param Request $request The HTTP request object.
     * @param Security $security The Symfony security component.
     * @return Response The response object.
     */
    #[Route('/register', name: 'security_register')]
    public function index(Request $request, Security $security): Response
    {
        // Get the result of form submission
        $formResult = $this->getFormResult($request, RegisterType::class, $security);

        // If there is a response, return it
        if ($formResult->getResponse()) return $formResult->getResponse();

        // Render the registration form
        return $this->render("security/register.html.twig", [
            "form" => $formResult->getForm()->createView()
        ]); 
    }

    /**
     * Checks if the user response is valid for registration.
     * 
     * @param User|null $user The user object.
     * @return bool True if the response is valid, otherwise false.
     */
    public function isValidResponse(?User $user): bool
    {
        if (is_null($user)) return false;

        $userExist = $this->userRepository->findOneBy([
            "phone" => $user->getPhone(), 
            "countryNumber" => $user->getCountryNumber()
        ]);

        return !$this->userRepository->findOneBy(["email" => $user->getEmail()]) && !$userExist;
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
     * Handles wrong response during registration.
     * 
     * @param User|null $user The user object.
     * @param string $message The error message.
     * @param mixed $additionalObject The additional object.
     * @return Response The response object.
     */
    public function getWrongResponse(?User $user, string $message, mixed $additionalObject): Response
    {
        return ($this->render("security/register.html.twig", [
            "form" => $additionalObject->createView(),
            "error" => $message
        ])->setStatusCode(Response::HTTP_UNAUTHORIZED));
    }

    /**
     * Handles correct response after successful registration.
     * 
     * @param User|null $user The user object.
     * @param string $message The success message.
     * @param mixed $additionalObject The additional object.
     * @return Response The response object.
     */
    public function getCorrectResponse(?User $user, string $message, mixed $additionalObject): Response
    {
        return ($this->redirectToRoute("app_about")
            ->setStatusCode(Response::HTTP_OK));
    }
}
