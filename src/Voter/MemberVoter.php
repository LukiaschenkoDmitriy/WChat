<?php

namespace App\Voter;
use App\Entity\Member;
use App\Entity\User;
use App\Enum\ChatRoleEnum;
use App\Enum\SiteRoleEnum;
use App\Voter\Abstract\AbstractWChatVoter;
use App\Voter\Object\SubjectVoterTags;
use Exception;

class MemberVoter extends AbstractWChatVoter {
    public const COLLECTION = "MEMBER_COLLECTION";
    public const GET = "MEMBER_GET";
    public const POST = "MEMBER_POST";
    public const PATCH = "MEMBER_PATCH";
    public const DELETE = "MEMBER_DELETE";
    public const IS_GRANTED_COLLECTION = "is_granted('".self::COLLECTION."', object)";
    public const IS_GRANTED_GET = "is_granted('".self::GET."', object)";
    public const IS_GRANTED_POST = "is_granted('".self::POST."', object)";
    public const IS_GRANTED_PATCH = "is_granted('".self::PATCH."', object)";
    public const IS_GRANTED_DELETE = "is_granted('".self::DELETE."', object)";
    public const SECURITY_GET_MESSAGE = "You cannot get this member because you are not the member, or you are not the administrator of the chat room the member is in.";
    public const SECURITY_POST_MESSAGE = "You cannot add a new member to this chat because you are not the administrator of this chat.";
    public const SECURITY_DELETE_MESSAGE = "You cannot remove this member because you are not the administrator of this chat room.";
    public const SECURITY_PATCH_MESSAGE = "You can't change the role of this participant remotely because you are not the administrator of this chat.";

    public function isSubjectSupports(mixed $subject): bool
    {
        return $subject instanceof Member;
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
        $userIsMember = $user === $subject->getUser();
        $userIsAdmin = $this->userIsAdmin($user, $subject);
        $userIsSiteAdmin = $this->security->isGranted(SiteRoleEnum::ADMIN);

        return $userIsMember || $userIsAdmin || $userIsSiteAdmin;
    }

    public function hasPostAccess(User $user, mixed $subject): bool
    {
        return $this->userIsAdminOrSystemAdmin( $user, $subject);
    }

    public function hasPatchAccess(User $user, mixed $subject): bool
    {
        return $this->userIsAdminOrSystemAdmin( $user, $subject);
    }

    public function hasDeleteAccess(User $user, mixed $subject): bool
    {
        $userIsMember = $user === $subject->getUser();
        return $this->userIsAdminOrSystemAdmin( $user, $subject) || $userIsMember;
    }

    public function userIsAdminOrSystemAdmin(User $user, mixed $subject): bool {
        $userIsAdmin = $this->userIsAdmin( $user, $subject);
        $userIsSiteAdmin = $this->security->isGranted(SiteRoleEnum::ADMIN);

        return $userIsAdmin || $userIsSiteAdmin;
    }

    public function userIsAdmin(User $user, mixed $subject): bool
    {
        return $subject->getChat()->getMembers()->exists(function ($key, Member $member) use ($user) {
            return (($member->getRole() == ChatRoleEnum::ADMIN) || ($member->getRole() == ChatRoleEnum::OWNER)) && $user === $member->getUser();
        });
    }
}