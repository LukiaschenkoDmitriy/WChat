<?php

namespace App\Voter;
use ApiPlatform\Doctrine\Orm\Paginator;
use App\Entity\File;
use App\Entity\User;
use App\Enum\SiteRoleEnum;
use App\Voter\Abstract\AbstractWChatVoter;
use App\Voter\Object\SubjectVoterTags;
use Exception;

class FileVoter extends AbstractWChatVoter {
    public const COLLECTION = "FILE_COLLECTION";
    public const GET = "FILE_GET";
    public const POST = "FILE_POST";
    public const PATCH = "FILE_PATCH";
    public const DELETE = "FILE_DELETE";
    public const IS_GRANTED_COLLECTION = "is_granted('".self::COLLECTION."', object)";
    public const IS_GRANTED_GET = "is_granted('".self::GET."', object)";
    public const IS_GRANTED_POST = "is_granted('".self::POST."', object)";
    public const IS_GRANTED_PATCH = "is_granted('".self::PATCH."', object)";
    public const IS_GRANTED_DELETE = "is_granted('".self::DELETE."', object)";
    public const SECURITY_GET_MESSAGE = "You can't get this file because you are not in the group where the file is located.";
    public const SECURITY_POST_MESSAGE = "You cannot create a file in this chat because you are not a member.";
    public const SECURITY_DELETE_MESSAGE = "You can delete this file because you are not in the chat room where the file is located.";
    public const SECURITY_PATCH_MESSAGE = "You cannot edit this file because you are not a member of the chat room where the file is located.";
    public function isSubjectSupports(mixed $subject): bool
    {
        return $subject instanceof File;
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

    public function hasGetAccess(User $user, mixed $subject): bool
    {
        return self::userHasAccess($user, $subject) || $this->security->isGranted(SiteRoleEnum::ADMIN);
    }

    public function hasPostAccess(User $user, mixed $subject): bool
    {
        return self::userHasAccess($user, $subject) || $this->security->isGranted(SiteRoleEnum::ADMIN);
    }

    public function hasDeleteAccess(User $user, mixed $subject): bool
    {
        return self::userHasAccess($user, $subject) || $this->security->isGranted(SiteRoleEnum::ADMIN);
    }

    public function hasPatchAccess(User $user, mixed $subject): bool
    {
        return self::userHasAccess($user, $subject) || $this->security->isGranted(SiteRoleEnum::ADMIN);
    }

    public static function userHasAccess(User $user, mixed $subject): bool {
        return $subject->getChat()->getMembers()->exists(function ($key, $member) use ($user) {
            return $member->getUser() === $user;
        });
    }
}