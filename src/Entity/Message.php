<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use App\Service\JsonEntityInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message implements JsonEntityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $message = null;

    #[ORM\Column(length: 255, nullable:true)]
    private ?int $type = null;

    #[ORM\Column(length: 255, nullable:true)]
    private ?string $url = null;

    #[ORM\Column(length: 255, nullable:true)]
    private ?string $pinMessage = null;

    #[ORM\Column(length: 255)]
    private ?string $time = null;

    #[ORM\ManyToOne(targetEntity: Chat::class, inversedBy:"messages")]
    private ?Chat $chat = null;

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

    public function toJson(): string
    {
        return json_encode([
            'id' => $this->getId(),
            'message' => $this->getMessage(),
            'type' => $this->getType(),
            'url' => $this->getUrl(),
            'pinMessage' => $this->getPinMessage(),
            'time' => $this->getTime(),
            "user" => [
                "id" => $this->getUser()->getId(),
                "email" => $this->getUser()->getEmail(),
                "firstName" => $this->getUser()->getFirstName(),
                "lastName" => $this->getUser()->getLastName(),
            ]
        ]);
    }
}
