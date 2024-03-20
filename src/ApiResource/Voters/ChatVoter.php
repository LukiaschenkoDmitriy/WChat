<?php

namespace Api\Voter;

use App\Entity\Chat;
use App\Entity\User;
use App\Enum\ChatRoleEnum;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ChatVoter extends Voter implements ResourceVoterInterface {
    public function __construct(
        private Security $security
    ) { }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $subject instanceof Chat && in_array($attribute, ["chat.getcollection", "chat.get", "chat.post", "chat.patch", "chat.delete"]);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        
        switch ($attribute) {
            case "chat.getcollection":
                return $this->hasGetCollectionAccess($user, $subject);
            case "chat.get":
                return $this->hasGetAccess($user, $subject);
            case "chat.post":
                return $this->hasPostAccess($user, $subject);
            case "chat.patch":
                return $this->hasPatchAccess($user, $subject);
            case "chat.delete":
                return $this->hasDeleteAccess($user, $subject);
        }

        return false;
    }

    public function hasGetCollectionAccess(User $user, object $object)
    {
        return true;
    }

    public function hasGetAccess(UserInterface $user, Chat $chat) {
        $userIsMember = $chat->getMembers()->exists(function ($key, $member) use ($user) {
            return $member->getUser() === $user;
        });

        return $userIsMember || $this->security->isGranted("ROLE_ADMIN");
    }

    public function hasPostAccess(User $user, object $object)
    {
        return true;
    }

    public function hasDeleteAccess(User $user, object $object)
    {
        return $this->isUserChatOwner($user, $object) || $this->security->isGranted("ROLE_ADMIN");
    }

    public function hasPatchAccess(User $user, object $object)
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