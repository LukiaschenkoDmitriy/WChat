<?php

namespace App\Entity;

use App\Repository\UserNotificationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserNotificationRepository::class)]
class UserNotification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $notifications = null;

    #[ORM\ManyToOne(targetEntity: Chat::class)]
    private ?Chat $chat = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNotifications(): ?int
    {
        return $this->notifications;
    }

    public function setNotifications(?int $notifications): static
    {
        $this->notifications = $notifications;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getChat(): ?Chat

    {
        return $this->chat;
    }

    public function setChat(Chat $chat): static
    {
        $this->chat = $chat;

        return $this;
    }
}
