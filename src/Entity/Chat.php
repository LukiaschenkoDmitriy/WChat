<?php

namespace App\Entity;

use App\Repository\ChatRepository;
use Doctrine\Common\Collections\ArrayCollection;
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

    #[ORM\Column(length: 255, nullable:true)]
    private ?string $avatar = null;

    #[ORM\OneToMany(targetEntity: Member::class, mappedBy:"chat")]
    private Collection $members;

    #[ORM\OneToMany(targetEntity: Message::class, mappedBy:"chat")]
    private Collection $messages;

    #[ORM\OneToMany(targetEntity: File::class, mappedBy:"chat")]
    private Collection $files;

    public function __construct()
    {
        $this->members = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->files = new ArrayCollection();
    }

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

    public function setAvatar(?string $avatar): static
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function setMembers(Collection $members): static
    {
        $this->members = $members;

        foreach ($members as $member) {
            $member->setChat($this);
        }

        return $this;
    }

    public function addMember(Member $member): static
    {
        if (!$this->members->contains($member)) {
            $this->members[] = $member;
            $member->setChat($this);
        }

        return $this;
    }

    public function removeMember(Member $member): static
    {
        if ($this->members->contains($member)) {
            $this->members->removeElement($member);
            if ($member->getChat() === $this) {
                $member->setChat(null);
            }
        }

        return $this;
    }

    public function setMessages(Collection $messages): static
    {
        $this->messages = $messages;

        foreach ($messages as $message) {
            $message->setChat($this);
        }

        return $this;
    }

    public function addMessage(Message $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setChat($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            if ($message->getChat() === $this) {
                $message->setChat(null);
            }
        }

        return $this;
    }

    public function setFiles(Collection $files): static
    {
        $this->files = $files;

        foreach ($files as $file) {
            $file->setChat($this);
        }

        return $this;
    }

    public function addFile(File $file): static
    {
        if (!$this->files->contains($file)) {
            $this->files[] = $file;
            $file->setChat($this);
        }

        return $this;
    }

    public function removeFile(File $file): static
    {
        if ($this->files->contains($file)) {
            $this->files->removeElement($file);
            if ($file->getChat() === $this) {
                $file->setChat(null);
            }
        }

        return $this;
    }
}
