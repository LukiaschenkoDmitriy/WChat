<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\MemberRepository;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;

#[ApiResource(
    normalizationContext:["groups" => ["member.read"]],
    denormalizationContext:["groups" => ["member.write"]],
    security:"is_granted('ROLE_ADMIN')"
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
    #[Groups(["chat.read", "member.read"])]
    private ?User $user = null;

    #[Groups(["user.read", "member.read"])]
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
