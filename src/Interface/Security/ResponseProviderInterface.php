<?php

namespace App\Interface\Security;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface ResponseProviderInterface
 * 
 * This interface defines methods for providing responses in security contexts.
 */
interface ResponseProviderInterface {
    /**
     * Get the response based on the user and security.
     * 
     * @param User|null $user The user entity (or null if not authenticated).
     * @param Security $security The Symfony security component.
     * @return Response The HTTP response object.
     */
    public function getResponse(?User $user, Security $security): Response;
    
    /**
     * Check if the provided response is valid.
     * 
     * @param User|null $user The user entity (or null if not authenticated).
     * @return bool True if the response is valid, false otherwise.
     */
    public function isValidResponse(?User $user): bool;

    /**
     * Get the wrong response based on the user, message, and additional object.
     * 
     * @param User|null $user The user entity (or null if not authenticated).
     * @param string $message The message to include in the response.
     * @param mixed $additionalObject Any additional object to include in the response.
     * @return Response The HTTP response object.
     */
    public function getWrongResponse(?User $user, string $message, mixed $additionalObject): Response;
    
    /**
     * Get the correct response based on the user, message, and additional object.
     * 
     * @param User|null $user The user entity (or null if not authenticated).
     * @param string $message The message to include in the response.
     * @param mixed $additionalObject Any additional object to include in the response.
     * @return Response The HTTP response object.
     */
    public function getCorrectResponse(?User $user, string $message, mixed $additionalObject): Response;
}
