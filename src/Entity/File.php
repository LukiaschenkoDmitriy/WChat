<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\FileRepository;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ApiResource(
    normalizationContext:["groups" => ["file.read"]],
    denormalizationContext:["groups" => ["file.write"]]
)]
#[ORM\Entity(repositoryClass: FileRepository::class)]
class File
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["chat.read", "file.read"])]
    private ?int $id = null;

    #[Groups(["chat.read", "file.read", "file.write"])]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Groups(["chat.read", "file.read", "file.write"])]
    #[ORM\Column(length: 255)]
    private ?string $url = null;

    #[Groups(["chat.read", "file.read", "file.write"])]
    #[ORM\Column(length: 255)]
    private ?string $format = null;

    #[Groups(["file.read"])]
    #[ORM\ManyToOne(targetEntity: Chat::class, inversedBy:"files")]
    private ?Chat $chat = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function setFormat(string $format): static
    {
        $this->format = $format;

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
