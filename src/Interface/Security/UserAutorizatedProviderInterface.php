<?php

namespace App\Interface\Security;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * Interface UserAutorizatedProviderInterface
 * 
 * This interface defines methods for providing user authorization information.
 */
interface UserAutorizatedProviderInterface {
    /**
     * Check if the user is authorized.
     * 
     * @param Security $security The Symfony security component.
     * @return bool True if the user is authorized, false otherwise.
     */
    public function isUserAutorizated(Security $security): bool;

    /**
     * Get the full user entity.
     * 
     * @param User|null $user The user entity (or null if not authenticated).
     * @return User|null The full user entity, or null if not available.
     */
    public function getFullUser(?User $user): ?User;
}
