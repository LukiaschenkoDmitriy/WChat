<?php

namespace App\Voter;
use ApiPlatform\Doctrine\Orm\Paginator;
use App\Entity\User;
use App\Enum\SiteRoleEnum;
use App\Voter\Abstract\AbstractWChatVoter;
use App\Voter\Object\SubjectVoterTags;

class UserVoter extends AbstractWChatVoter
{
    public const COLLECTION = "USER_COLLECTION";
    public const GET = "USER_GET";
    public const POST = "USER_POST";
    public const PATCH = "USER_PATCH";
    public const DELETE = "USER_DELETE";
    public const IS_GRANTED_COLLECTION = "is_granted('".self::COLLECTION."', object)";
    public const IS_GRANTED_GET = "is_granted('".self::GET."', object)";
    public const IS_GRANTED_POST = "is_granted('".self::POST."', object)";
    public const IS_GRANTED_PATCH = "is_granted('".self::PATCH."', object)";
    public const IS_GRANTED_DELETE = "is_granted('".self::DELETE."', object)";
    public const SECURITY_COLLECTION_MESSAGE = "You do not have permission to fulfill this request.";
    public const SECURITY_GET_MESSAGE = "You cannot retrieve this user's data.";
    public const SECURITY_POST_MESSAGE = "You do not have permission to fulfill this request.";
    public const SECURITY_DELETE_MESSAGE = "You cannot delete another user's account.";
    public const SECURITY_PATCH_MESSAGE = "You are not authorized to change another user's data.";

    public function isSubjectSupports(mixed $subject): bool
    {
        return $subject instanceof User;
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

    public function hasGetCollectionAccess(User $user, Paginator $paginator): bool
    {
        return $this->security->isGranted(SiteRoleEnum::ADMIN);
    } 

    public function hasGetAccess(User $user, mixed $subject): bool
    {
        return $user === $subject || $this->security->isGranted(SiteRoleEnum::ADMIN);
    }

    public function hasPostAccess(User $user, mixed $subject): bool
    {
        return $this->security->isGranted(SiteRoleEnum::ADMIN);
    }

    public function hasPatchAccess(User $user, mixed $subject): bool
    {
        return $user === $subject || $this->security->isGranted(SiteRoleEnum::ADMIN);
    }

    public function hasDeleteAccess(User $user, mixed $subject): bool
    {
        return $user === $subject || $this->security->isGranted(SiteRoleEnum::ADMIN);
    }
}