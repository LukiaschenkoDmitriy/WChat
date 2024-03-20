<?php

namespace App\Voter;

use ApiPlatform\Doctrine\Orm\Paginator;
use App\Entity\Chat;
use App\Entity\User;
use App\Enum\ChatRoleEnum;
use Exception;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ChatVoter extends Voter {
    private const COLLECTION = "CHAT_COLLECTION";
    private const GET = "CHAT_GET";
    private const POST = "CHAT_POST";
    private const PATCH = "CHAT_PATCH";
    private const DELETE = "CHAT_DELETE";

    public function __construct(
        private Security $security
    ) { }

    protected function supports(string $attribute, mixed $subject): bool
    {
        $isPaginator = $subject instanceof Paginator;
        $isChat = $subject instanceof Chat;
        $hasCorrectAttribute = in_array($attribute, [
            self::COLLECTION,
            self::GET,
            self::POST,
            self::PATCH,
            self::DELETE
        ]);

        return (( $isPaginator || $isChat ) && $hasCorrectAttribute) || $attribute == self::POST;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if ($subject instanceof Paginator) {
            return $this->hasGetCollectionAccess($user, $subject);
        }

        if ($subject instanceof Chat) {
            switch ($attribute) {
                case self::GET:
                    return $this->hasGetAccess($user, $subject);
                    break;
                case self::PATCH:
                    return $this->hasPatchAccess($user, $subject);
                    break;
                case self::DELETE:
                    return $this->hasDeleteAccess($user, $subject);
                    break;
            }
        }

        if ($attribute == self::POST) {
            return $this->hasPostAccess($user);
        }

        return false;
    }

    public function hasGetCollectionAccess(User $user, object $object): bool
    {
        return true;
    }

    public function hasGetAccess(UserInterface $user, Chat $chat): bool {
        $userIsMember = $chat->getMembers()->exists(function ($key, $member) use ($user) {
            return $member->getUser() === $user;
        });
    
        return $userIsMember || $this->security->isGranted("ROLE_ADMIN");
    }

    public function hasPostAccess(User $user): bool
    {
        return true;
    }

    public function hasDeleteAccess(User $user, object $object): bool
    {
        return $this->isUserChatOwner($user, $object) || $this->security->isGranted("ROLE_ADMIN");
    }

    public function hasPatchAccess(User $user, object $object): bool
    {
        return $this->isUserChatOnwerOrAdmin($user, $object) || $this->security->isGranted("ROLE_ADMIN");
    }

    public function isUserChatAdmin(UserInterface $user, Chat $chat): bool {
        return $chat->getMembers()->exists(function ($key, $member) use ($user) {
            return $member->getUser() === $user && $member->getRole() === ChatRoleEnum::ADMIN;
        });
    }

    public function isUserChatOwner(UserInterface $user, Chat $chat): bool {
        return $chat->getMembers()->exists(function ($key, $member) use ($user) {
            return $member->getUser() === $user && ($member->getRole() === ChatRoleEnum::OWNER);
        });
    }

    public function isUserChatOnwerOrAdmin(UserInterface $user, Chat $chat): bool {
        return $this->isUserChatAdmin($user, $chat) || $this->isUserChatOwner($user, $chat);
    }

    public function isUserChatMember(UserInterface $user, Chat $chat): bool {
        return $chat->getMembers()->exists(function ($key, $member) use ($user) {
            return $member->getUser() === $user;
        });
    }

    public function isMessageUser(UserInterface $user, Chat $chat): bool {
        return $chat->getMessages()->exists(function ($key, $message) use ($user) {
            return $message->getUser() === $user;
        });
    }
}