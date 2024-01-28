<?php

namespace App\Entity;

use App\Enum\CollectionOperationEnum;
use App\Exception\WChatEntityCollectionException;
use App\Repository\ChatRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChatRepository::class)]
class Chat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $avatar = null;

    #[ORM\OneToMany(targetEntity: Member::class, mappedBy: 'member')]
    private Collection $members;

    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: 'message')]
    private Collection $messages;

    #[ORM\OneToMany(targetEntity: File::class, mappedBy: 'file')]
    private Collection $files;

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

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): static
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function addMember(Member $member): static
    {
        if ($this->members->contains($member)) {
            throw new WChatEntityCollectionException($this::class, Member::class, CollectionOperationEnum::ADD);
        }
        $this->members->add($member);
        return $this;
    }

    public function removeMember(Member $member): static
    {
        if (!$this->members->contains($member)) {
            throw new WChatEntityCollectionException($this::class, Member::class, CollectionOperationEnum::REMOVE);
        }

        $this->members->removeElement($member);
        return $this;
    }

    public function addFile(File $file): static
    {
        if ($this->files->contains($file)) {
            throw new WChatEntityCollectionException($this::class, File::class, CollectionOperationEnum::ADD);
        }
        $this->files->add($file);
        return $this;
    }

    public function removeFile(File $file): static
    {
        if (!$this->files->contains($file)) {
            throw new WChatEntityCollectionException($this::class, File::class, CollectionOperationEnum::REMOVE);
        }

        $this->files->removeElement($file);
        return $this;
    }

    public function addMessage(Message $message): static
    {
        if ($this->messages->contains($message)) {
            throw new WChatEntityCollectionException($this::class, Message::class, CollectionOperationEnum::ADD);
        }
        $this->messages->add($message);
        return $this;
    }

    public function removeUser(Message $message): static
    {
        if (!$this->messages->contains($message)) {
            throw new WChatEntityCollectionException($this::class, Message::class, CollectionOperationEnum::REMOVE);
        }

        $this->messages->removeElement($message);
        return $this;
    }
}
