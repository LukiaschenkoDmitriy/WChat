<?php

namespace App\Voter;

use App\Entity\Chat;
use App\Entity\User;

use App\Enum\ChatRoleEnum;

use App\Enum\SiteRoleEnum;
use App\Voter\Abstract\AbstractWChatVoter;
use App\Voter\Object\SubjectVoterTags;

class ChatVoter extends AbstractWChatVoter {

    public const COLLECTION = "CHAT_COLLECTION";
    public const GET = "CHAT_GET";
    public const POST = "CHAT_POST";
    public const PATCH = "CHAT_PATCH";
    public const DELETE = "CHAT_DELETE";
    public const IS_GRANTED_COLLECTION = "is_granted('".self::COLLECTION."', object)";
    public const IS_GRANTED_GET = "is_granted('".self::GET."', object)";
    public const IS_GRANTED_POST = "is_granted('".self::POST."', object)";
    public const IS_GRANTED_PATCH = "is_granted('".self::PATCH."', object)";
    public const IS_GRANTED_DELETE = "is_granted('".self::DELETE."', object)";
    public const SECURITY_GET_MESSAGE = "You cannot access this chat because you are not a member of this chat.";
    public const SECURITY_DELETE_MESSAGE = "You cannot delete this chat because you are not the creator of this chat.";
    public const SECURITY_PATCH_MESSAGE = "You cannot edit this chat because you are not the administrator of this chat.";

    public function isSubjectSupports(mixed $subject): bool
    {
        return $subject instanceof Chat;
    }

    public function getSubjectVoterTags(): SubjectVoterTags
    {
        return new SubjectVoterTags(
            self::COLLECTION,
            self::GET,
            self::POST,
            self::DELETE,
            self::PATCH
        );
    }

    public function hasGetAccess(User $user, mixed $subject): bool {
        $userIsMember = $subject->getMembers()->exists(function ($key, $member) use ($user) {
            return $member->getUser() === $user;
        });
    
        return $userIsMember || $this->security->isGranted(SiteRoleEnum::ADMIN);
    }

    public function hasDeleteAccess(User $user, mixed $subject): bool
    {
        return self::isUserChatOwner($user, $subject) || $this->security->isGranted(SiteRoleEnum::ADMIN);
    }

    public function hasPatchAccess(User $user, mixed $subject): bool
    {
        return self::isUserChatOnwerOrAdmin($user, $subject) || $this->security->isGranted(SiteRoleEnum::ADMIN);
    }

    public static function isUserChatAdmin(User $user, Chat $chat): bool {
        return $chat->getMembers()->exists(function ($key, $member) use ($user) {
            return $member->getUser() === $user && $member->getRole() === ChatRoleEnum::ADMIN;
        });
    }

    public static function isUserChatOwner(User $user, Chat $chat): bool {
        return $chat->getMembers()->exists(function ($key, $member) use ($user) {
            return $member->getUser() === $user && ($member->getRole() === ChatRoleEnum::OWNER);
        });
    }

    public static function isUserChatOnwerOrAdmin(User $user, Chat $chat): bool {
        return self::isUserChatAdmin($user, $chat) || self::isUserChatOwner($user, $chat);
    }
}