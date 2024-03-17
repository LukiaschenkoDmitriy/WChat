<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\MessageRepository;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ApiResource(
    normalizationContext:["groups" => ["message.read"]],
    denormalizationContext:["groups" => ["message.write"]]
)]
#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["chat.read", "message.read", "user.read"])]
    private ?int $id = null;

    #[Groups(["chat.read", "message.read", "message.write", "user.read"])]
    #[ORM\Column(length: 255)]
    private ?string $message = null;

    #[Groups(["chat.read", "message.read", "message.write", "user.read"])]
    #[ORM\Column(length: 255, nullable:true)]
    private ?int $type = null;

    #[Groups(["chat.read", "message.read", "message.write", "user.read"])]
    #[ORM\Column(length: 255, nullable:true)]
    private ?string $url = null;

    #[Groups(["chat.read", "message.read", "message.write", "user.read"])]
    #[ORM\Column(length: 255, nullable:true)]
    private ?string $pinMessage = null;

    #[Groups(["chat.read", "message.read", "message.write", "user.read"])]
    #[ORM\Column(length: 255)]
    private ?string $time = null;

    #[Groups(["user.read", "message.read"])]
    #[ORM\ManyToOne(targetEntity: Chat::class, inversedBy:"messages")]
    private ?Chat $chat = null;

    #[Groups(["chat.read", "message.read"])]
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy:"messages")]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(?int $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getPinMessage(): ?string
    {
        return $this->pinMessage;
    }

    public function setPinMessage(?string $pinMessage): static
    {
        $this->pinMessage = $pinMessage;

        return $this;
    }

    public function getTime(): ?string
    {
        return $this->time;
    }

    public function setTime(?string $time): static
    {
        $this->time = $time;
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    }
}
