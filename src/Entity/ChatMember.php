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

    #[ORM\ManyToOne(targetEntity: Chat::class, cascade:["remove"])]
    private ?Chat $chat = null;

    #[ORM\ManyToOne(targetEntity: User::class, cascade:["remove"])]
    private ?User $user = null;

    # role_id = 0 - default user
    # role_id = 1 - active user (acsess for add files)
    # role_id = 2 - admin (acsesss for add users)
    # role_id = 3 - creator (full acsess)
    #[ORM\Column]
    private ?int $role_id = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

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
