<?php

namespace App\Interface\Security;

use App\Entity\User;

use Symfony\Bundle\SecurityBundle\Security;

interface UserAutorizatedProviderInterface {
    public function isUserAutorizated(Security $security): bool;

    public function getFullUser(?User $user): ?User;
}