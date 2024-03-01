<?php

namespace App\Interface\Security;

use App\Entity\User;

/**
 * Interface RegisterProviderInterface
 * 
 * This interface defines a method for registering users.
 */
interface RegisterProviderInterface {
    /**
     * Register a user.
     * 
     * @param User $user The user entity to register.
     * @return void
     */
    public function registerUser(User $user): void;
}
