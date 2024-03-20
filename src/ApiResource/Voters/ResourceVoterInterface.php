<?php

namespace Api\Voter;

use App\Entity\User;

interface ResourceVoterInterface {
    public function hasGetCollectionAccess(User $user, object $object);
    public function hasGetAccess(User $user, object $object);
    public function hasPostAccess(User $user, object $object);
    public function hasPatchAccess(User $user, object $object);
    public function hasDeleteAccess(User $user, object $object);
}