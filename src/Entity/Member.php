<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Controller\Api\Collection\CollectionMemberController;
use App\Repository\MemberRepository;

use App\Voter\MemberVoter;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;

#[ApiResource(
    normalizationContext:["groups" => ["member.read"]],
    denormalizationContext:["groups" => ["member.write"]],
)]
#[GetCollection(
    security: MemberVoter::IS_GRANTED_COLLECTION,
    controller: CollectionMemberController::class
)]
#[Get(
    security: MemberVoter::IS_GRANTED_GET,
    securityMessage: MemberVoter::SECURITY_GET_MESSAGE
)]
#[Post(
    securityPostDenormalize: MemberVoter::IS_GRANTED_POST,
    securityPostDenormalizeMessage: MemberVoter::SECURITY_POST_MESSAGE
)]
#[Patch(
    security: MemberVoter::IS_GRANTED_PATCH,
    securityMessage: MemberVoter::SECURITY_PATCH_MESSAGE
)]
#[Delete(
    security: MemberVoter::IS_GRANTED_DELETE,
    securityMessage: MemberVoter::SECURITY_DELETE_MESSAGE
)]
#[ORM\Entity(repositoryClass: MemberRepository::class)]
class Member
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["chat.read", "member.read", "user.read"])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(["chat.read", "member.read", "member.write", "user.read"])]
    private ?string $role = "CHAT_MEMBER_ROLE";

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy:"members")]
    #[Groups(["chat.read", "member.read", "member.write"])]
    private ?User $user = null;

    #[Groups(["user.read", "member.read", "member.write"])]
    #[ORM\ManyToOne(targetEntity: Chat::class, inversedBy:"members")]
    private ?Chat $chat = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        $user->addMember($this);
        return $this;
    }

    public function getChat(): ?Chat
    {
        return $this->chat;
    }

    public function setChat(?Chat $chat): static
    {
        $this->chat = $chat;
        $chat->addMember($this);
        return $this;
    }

    public function isUserAreMember(UserInterface $user):bool {
        return $this->getUser() === $user;
    }
}
