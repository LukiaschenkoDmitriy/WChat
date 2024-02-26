<?php

namespace App\Interface\Security;

use App\Entity\User;

interface RegisterProviderInterface {
    public function registerUser(User $user): void;
}