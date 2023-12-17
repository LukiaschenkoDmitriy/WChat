<?php

namespace App\Entity;

use App\Repository\ChatFileRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChatFileRepository::class)]
class ChatFile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Chat::class)]
    private ?Chat $chat = null;

    #[ORM\Column(length: 255)]
    private ?string $file_name = null;

    #[ORM\Column(length: 255)]
    private ?string $kategory = null;

    #[ORM\Column(length: 255)]
    private ?string $file_url = null;

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

    public function getFileName(): ?string
    {
        return $this->file_name;
    }

    public function setFileName(string $file_name): static
    {
        $this->file_name = $file_name;

        return $this;
    }

    public function getKategory(): ?string
    {
        return $this->kategory;
    }

    public function setKategory(string $kategory): static
    {
        $this->kategory = $kategory;

        return $this;
    }

    public function getFileUrl(): ?string
    {
        return $this->file_url;
    }

    public function setFileUrl(string $file_url): static
    {
        $this->file_url = $file_url;

        return $this;
    }
}
