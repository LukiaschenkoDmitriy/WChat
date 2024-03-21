<?php

namespace App\Voter\Interface;

use ApiPlatform\Doctrine\Orm\Paginator;
use App\Entity\User;

interface ResourceVoterInterface {
    public function hasPostAccess(User $user): bool;
    public function hasGetAccess(User $user, mixed $subject): bool;
    public function hasPatchAccess(User $user, mixed $subject): bool;
    public function hasDeleteAccess(User $user, mixed $subject): bool;
    public function hasGetCollectionAccess(User $user, Paginator $paginator): bool;
}