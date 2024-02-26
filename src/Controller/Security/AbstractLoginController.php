<?php

namespace App\Controller\Security;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;

/**
 * Abstract class providing common functionality for login controllers.
 */
abstract class AbstractLoginController extends AbstractFormSecurityController 
{
    /**
     * Gets the full user details.
     *
     * @param User|null $user The user object.
     * @return User|null The full user details.
     */
    public function getFullUser(?User $user): ?User
    {
        // Find and return the full user details from the user repository
        return $this->userRepository->findOneBy(["email" => $user->getEmail()]);
    }

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
            return $this->getWrongResponse($user, $this->getAuhorizatedMessage(), $this->getAdditionalObject());
        }
        
        // Check if the user is not valid
        if (!$this->isValidResponse($user)) {
            // If not, return a wrong response with invalid credentials message
            return $this->getWrongResponse($user, $this->getWrongResponseMessage(), $this->getAdditionalObject());
        }

        // If user is authorized and valid, return a correct response
        return $this->getCorrectResponse($user, $this->getCorrectResponseMessage(), $this->getAdditionalObject());
    }

    /**
     * Gets the message for wrong response.
     *
     * @return string The message for wrong response.
     */
    public function getWrongResponseMessage(): string
    {
        return "User or password uncorrect";
    }

    /**
     * Gets the message for correct response.
     *
     * @return string The message for correct response.
     */
    public function getCorrectResponseMessage(): string
    {
        return "Autorization is correct";
    }
}
