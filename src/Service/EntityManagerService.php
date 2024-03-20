<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class EntityManagerService {
    public function __construct(
        private EntityManagerInterface $entityManagerInterface
    ) { }

    public function getFullyUser(string $userEmail): User | null {
        $userRepository = $this->entityManagerInterface->getRepository(User::class);
        return $userRepository->findOneBy(["email" => $userEmail]);
    }
}