<?php

namespace App\Entity;

use App\Repository\ChatMemberRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChatMemberRepository::class)]
class ChatMember
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $chat_id = null;

    #[ORM\Column]
    private ?int $user_id = null;

    #[ORM\Column]
    private ?int $role_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChatId(): ?int
    {
        return $this->chat_id;
    }

    public function setChatId(int $chat_id): static
    {
        $this->chat_id = $chat_id;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getRoleId(): ?int
    {
        return $this->role_id;
    }

    public function setRoleId(int $role_id): static
    {
        $this->role_id = $role_id;

        return $this;
    }
}
