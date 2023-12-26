<?php

namespace App\Service;
use App\Entity\Chat;
use App\Entity\ChatMember;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class RoleManager
{
    private EntityManagerInterface $entityManagerInterface;
    public function __construct(
        EntityManagerInterface $entityManagerInterface) 
    {
        $this->entityManagerInterface = $entityManagerInterface;
    }

    public function isUserChatMember(Chat $chat, User $user): bool {
        return $this->entityManagerInterface->getRepository(ChatMember::class)->findOneBy(["chat" => $chat, "user" => $user]) != null;
    }

    public function getUserRoleId(Chat $chat, User $user): int {
        if ($this->isUserChatMember($chat, $user)) {
            return $this->entityManagerInterface->getRepository(ChatMember::class)->findOneBy(["chat" => $chat, "user" => $user])->getRoleId();
        } return -1;
    }

    public function hasUserRole(Chat $chat, User $user, int $role_id) {
        return $this->getUserRoleId($chat, $user) >= $role_id;
    }
}