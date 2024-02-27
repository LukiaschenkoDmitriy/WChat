<?php

namespace Api\Controller\Security;

use Api\Interface\ApiRequestValidator;
use App\Controller\Security\AbstractSecurityController;
use App\Entity\User;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;

/**
 * Abstract class providing common functionality for API security controllers.
 */
abstract class AbstractApiSecurityController extends AbstractSecurityController implements ApiRequestValidator
{
    /**
     * Gets the response based on user authentication and authorization.
     *
     * @param User|null $user The user object.
     * @param Security $security The Symfony security component.
     * @return Response The response object.
     */
    public function getResponse(?User $user, Security $security): Response
    {
        // Check if the user is already authorized
        if ($this->isUserAutorizated($security)) {
            // If so, return a wrong response with authorization message
            return $this->getWrongResponse($user, $this->getAuhorizatedMessage(), null);
        }

        // Check if the user is valid
        if (!$this->isValidResponse($user)) {
            // If not, return a wrong response with invalid credentials message
            return $this->getWrongResponse($user, $this->getWrongResponseMessage(), null);
        }

        // If user is authorized and valid, return a correct response
        return $this->getCorrectResponse($user, $this->getCorrectResponseMessage(), null);
    }

    /**
     * Gets the message for wrong response.
     *
     * @return string The message for wrong response.
     */
    public function getWrongResponseMessage(): string
    {
        return "Access denied. Invalid login or password.";
    }

    /**
     * Gets the message for correct response.
     *
     * @return string The message for correct response.
     */
    public function getCorrectResponseMessage(): string
    {
        return "You have successfully logged in.";
    }

    /**
     * Gets the full user details.
     *
     * @param User|null $user The user object.
     * @return User|null The full user details.
     */
    public function getFullUser(?User $user): ?User
    {
        // Check if the user object is null
        if (is_null($user)) return null;

        // Find and return the full user details from the user repository
        return $this->userRepository->findOneBy(["email" => $user->getEmail()]);
    }
}
