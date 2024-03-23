<?php

namespace App\Voter;

use App\Entity\Member;
use App\Entity\Message;
use App\Entity\User;
use App\Enum\SiteRoleEnum;
use App\Voter\Abstract\AbstractWChatVoter;
use App\Voter\Object\SubjectVoterTags;

class MessageVoter extends AbstractWChatVoter {
    public const COLLECTION = "MESSAGE_COLLECTION";
    public const GET = "MESSAGE_GET";
    public const POST = "MESSAGE_POST";
    public const PATCH = "MESSAGE_PATCH";
    public const DELETE = "MESSAGE_DELETE";
    public const IS_GRANTED_COLLECTION = "is_granted('".self::COLLECTION."', object)";
    public const IS_GRANTED_GET = "is_granted('".self::GET."', object)";
    public const IS_GRANTED_POST = "is_granted('".self::POST."', object)";
    public const IS_GRANTED_PATCH = "is_granted('".self::PATCH."', object)";
    public const IS_GRANTED_DELETE = "is_granted('".self::DELETE."', object)";
    public const SECURITY_GET_MESSAGE = "You cannot receive this message because you are not the author.";
    public const SECURITY_POST_MESSAGE = "You can't create a message in this chat because you're not in it.";
    public const SECURITY_DELETE_MESSAGE = "You cannot delete this message because you are not the author.";
    public const SECURITY_PATCH_MESSAGE = "You cannot edit this message because you are not the author.";

    public function isSubjectSupports(mixed $subject): bool
    {
        return $subject instanceof Message;
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
        return $subject->getUser() === $user || $this->security->isGranted(SiteRoleEnum::ADMIN);
    }

    public function hasPostAccess(User $user, mixed $subject): bool
    {
        return $subject->getChat()->getMembers()->exists(function ($key, Member $member) use ($user) {
            return $member->getUser() === $user;
        }) || $this->security->isGranted(SiteRoleEnum::ADMIN);
    }

    public function hasPatchAccess(User $user, mixed $subject): bool
    {
        return $subject->getUser() === $user || $this->security->isGranted(SiteRoleEnum::ADMIN);
    }

    public function hasDeleteAccess(User $user, mixed $subject): bool
    {
        return $subject->getUser() === $user || $this->security->isGranted(SiteRoleEnum::ADMIN);
    }
}