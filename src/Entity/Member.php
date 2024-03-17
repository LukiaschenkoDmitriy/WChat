<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\MemberRepository;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ApiResource(
    normalizationContext:["groups" => ["member.read"]],
    denormalizationContext:["groups" => ["member.write"]]
)]
#[ORM\Entity(repositoryClass: MemberRepository::class)]
class Member
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["chat.read", "member.read", "user.read"])]
    private ?int $id = null;

    // -1 - member who don't access to send message
    // 0 - member
    // 1 - admin
    // 2 - owner
    #[ORM\Column]
    #[Groups(["chat.read", "member.read", "member.write", "user.read"])]
    private ?int $role = null;

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

    public function getRole(): ?int
    {
        return $this->role;
    }

    public function setRole(int $role): static
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
        return $this;
    }

    public function getChat(): ?Chat
    {
        return $this->chat;
    }

    public function setChat(?Chat $chat): static
    {
        $this->chat = $chat;
        return $this;
    }
}
