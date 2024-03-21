<?php

namespace App\Voter;

use ApiPlatform\Doctrine\Orm\Paginator;

use App\Entity\Chat;
use App\Entity\User;
use App\Enum\ChatRoleEnum;
use App\Voter\Abstract\AbstractWChatVoter;
use App\Voter\Object\SubjectVoterTags;

class ChatVoter extends AbstractWChatVoter {

    public function isSubjectSupports(mixed $subject): bool
    {
        return $subject instanceof Chat;
    }

    public function getSubjectVoterTags(): SubjectVoterTags
    {
        return new SubjectVoterTags(
            "CHAT_COLLECTION",
            "CHAT_GET",
            "CHAT_POST",
            "CHAT_PATCH",
            "CHAT_DELETE"
        );
    }

    public function hasGetCollectionAccess(User $user, Paginator $paginator): bool
    {
        return true;
    }

    public function hasGetAccess(User $user, mixed $subject): bool {
        $userIsMember = $subject->getMembers()->exists(function ($key, $member) use ($user) {
            return $member->getUser() === $user;
        });
    
        return $userIsMember || $this->security->isGranted("ROLE_ADMIN");
    }

    public function hasPostAccess(User $user): bool
    {
        return true;
    }

    public function hasDeleteAccess(User $user, mixed $subject): bool
    {
        return $this->isUserChatOwner($user, $subject) || $this->security->isGranted("ROLE_ADMIN");
    }

    public function hasPatchAccess(User $user, mixed $subject): bool
    {
        return $this->isUserChatOnwerOrAdmin($user, $subject) || $this->security->isGranted("ROLE_ADMIN");
    }

    public function isUserChatAdmin(User $user, Chat $chat): bool {
        return $chat->getMembers()->exists(function ($key, $member) use ($user) {
            return $member->getUser() === $user && $member->getRole() === ChatRoleEnum::ADMIN;
        });
    }

    public function isUserChatOwner(User $user, Chat $chat): bool {
        return $chat->getMembers()->exists(function ($key, $member) use ($user) {
            return $member->getUser() === $user && ($member->getRole() === ChatRoleEnum::OWNER);
        });
    }

    public function isUserChatOnwerOrAdmin(User $user, Chat $chat): bool {
        return $this->isUserChatAdmin($user, $chat) || $this->isUserChatOwner($user, $chat);
    }

    public function isUserChatMember(User $user, Chat $chat): bool {
        return $chat->getMembers()->exists(function ($key, $member) use ($user) {
            return $member->getUser() === $user;
        });
    }
}