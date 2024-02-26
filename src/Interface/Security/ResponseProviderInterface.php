<?php

namespace App\Interface\Security;

use App\Entity\User;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;

interface ResponseProviderInterface {
    public function getResponse(?User $user, Security $security): Response;
    public function isValidResponse(?User $user): bool;

    public function getWrongResponse(?User $user, string $message, mixed $additionalObject): Response;
    public function getCorrectResponse(?User $user, string $message, mixed $additionalObject): Response;
}