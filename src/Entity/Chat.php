<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;


// eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE3MTEwNDgyNTMsImV4cCI6MTcxMTA4NDI1Mywicm9sZXMiOlsiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoiYWRtaW5AZ21haWwuY29tIn0.qywN0Y8Icwu1ZG1dcsDOSigpLLYozF7g-slSvlbr-pLTIY7rYUppB9anOr-Ny_Df52pfUEG783tVW4-6srAYmB-aGFn_cn1ttoVprsOXoA-3y14jzKdqpCJW0z9AvwtLj3A4wCYed_dcyECE67d7XDsKANznYzTm0rDEk53XlwuuWLbLQhTpjp9wvTtm_NG29S3CudgrgCNwO5h_TpgoY2OxPelAwwnnf5BOPN9pGJpkVBhje8io6A_xjZhpJ7Y0xv3c5J2UkgBWtP7AecUm2uYcrmyWxac0-nopt5tSLAs-a5nVbZ9uf567x4wjCMSSnfOenvP-E8KFbwb0xAF6mA

use App\Controller\Api\Collection\CollectionChatController;
use App\Controller\Api\Post\PostChatController;

use App\Enum\ChatRoleEnum;
use App\Repository\ChatRepository;
use App\Voter\ChatVoter;

use Doctrine\Common\Collections\ArrayCollection; 
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;

#[ApiResource(
    normalizationContext: ['groups' => ['chat.read']],
    denormalizationContext: ['groups' => ['chat.write']],
)]
#[GetCollection(
    security: ChatVoter::IS_GRANTED_COLLECTION,
    controller: CollectionChatController::class
)]
#[Get(
    security: ChatVoter::IS_GRANTED_GET,
    securityMessage: ChatVoter::SECURITY_GET_MESSAGE
)]
#[Post(
    securityPostDenormalize: ChatVoter::IS_GRANTED_POST,
    controller: PostChatController::class
)]
#[Delete(
    security: ChatVoter::IS_GRANTED_DELETE,
    securityMessage: ChatVoter::SECURITY_DELETE_MESSAGE
)]
#[Patch(
    security: ChatVoter::IS_GRANTED_PATCH,
    securityMessage: ChatVoter::SECURITY_PATCH_MESSAGE
)]
#[ORM\Entity(repositoryClass: ChatRepository::class)]
class Chat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['chat.read', "file.read", "member.read", "message.read", "user.read"])]
    private ?int $id = null;

    #[Groups(["chat.read", "chat.write", "file.read", "member.read", "message.read", "user.read"])]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Groups(["chat.read", "chat.write", "file.read", "member.read", "message.read", "user.read"])]
    #[ORM\Column(length: 255, nullable:true)]
    private ?string $avatar = null;

    #[Groups(["chat.read", "file.read", "member.read", "message.read", "user.read"])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $folder = null;

    #[Groups(["chat.read"])]
    #[ORM\OneToOne(targetEntity: Message::class)]
    private ?Message $lastMessage = null;

    #[Groups(["chat.read"])]
    #[ORM\OneToMany(targetEntity: Member::class, mappedBy:"chat", cascade: ["remove"])]
    private Collection $members;        

    #[Groups(["chat.read"])]
    #[ORM\OneToMany(targetEntity: Message::class, mappedBy:"chat", cascade: ["remove"])]
    private Collection $messages;

    #[Groups(["chat.read"])]
    #[ORM\OneToMany(targetEntity: File::class, mappedBy:"chat", cascade: ["remove"])]
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

    public function getFolder(): ?string
    {
        return $this->folder;
    }

    public function setFolder(?string $folder): static
    {
        $this->folder = $folder;
        return $this;
    }

    public function getLastMessage(): ?Message
    {
        return $this->lastMessage;
    }

    public function setLastMessage(?Message $message): static
    {
        $this->lastMessage = $message;
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
            $this->setLastMessage($message);
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

            if (count($this->messages) != 0) {
                $this->setLastMessage(end($this->messages));
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

    public function isUserChatAdmin(UserInterface $user): bool {
        return $this->getMembers()->exists(function ($key, $member) use ($user) {
            return $member->getUser() === $user && $member->getRole() === ChatRoleEnum::ADMIN;
        });
    }

    public function isUserChatOwner(UserInterface $user): bool {
        return $this->getMembers()->exists(function ($key, $member) use ($user) {
            return $member->getUser() === $user && ($member->getRole() === ChatRoleEnum::OWNER);
        });
    }

    public function isUserChatOnwerOrAdmin(UserInterface $user): bool {
        return $this->isUserChatAdmin($user) || $this->isUserChatOwner($user);
    }

    public function isUserChatMember(UserInterface $user):bool {
        return $this->getMembers()->exists(function ($key, $member) use ($user) {
            return $member->getUser() === $user;
        });
    }

    public function isMessageUser(UserInterface $user):bool {
        return $this->getMessages()->exists(function ($key, $message) use ($user) {
            return $message->getUser() === $user;
        });
    }
}