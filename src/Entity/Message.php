<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Controller\Api\Collection\CollectionMessageController;
use App\Controller\Api\Post\PostMessageController;
use App\Repository\MessageRepository;

use App\Voter\MessageVoter;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;

#[ApiResource(
    normalizationContext:["groups" => ["message.read"]],
    denormalizationContext:["groups" => ["message.write"]]
)]
#[GetCollection(
    security: MessageVoter::IS_GRANTED_COLLECTION,
    controller: CollectionMessageController::class
)]
#[Get(
    security: MessageVoter::IS_GRANTED_GET,
    securityMessage: MessageVoter::SECURITY_GET_MESSAGE
)]
#[Post(
    securityPostDenormalize: MessageVoter::IS_GRANTED_POST,
    securityPostDenormalizeMessage: MessageVoter::SECURITY_POST_MESSAGE,
    controller: PostMessageController::class
)]
#[Patch(
    security: MessageVoter::IS_GRANTED_PATCH,
    securityMessage: MessageVoter::SECURITY_PATCH_MESSAGE
)]
#[Delete(
    security: MessageVoter::IS_GRANTED_DELETE,
    securityMessage: MessageVoter::SECURITY_DELETE_MESSAGE
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

    #[Groups(["chat.read", "message.read", "user.read"])]
    #[ORM\Column(length: 255, nullable:true)]
    private ?int $type = null;

    #[Groups(["chat.read", "message.read", "user.read"])]
    #[ORM\Column(length: 255, nullable:true)]
    private ?string $url = null;

    #[Groups(["chat.read", "message.read", "user.read"])]
    #[ORM\Column(length: 255, nullable:true)]
    private ?string $pinMessage = null;

    #[Groups(["chat.read", "message.read", "user.read"])]
    #[ORM\Column(length: 255)]
    private ?string $time = null;

    #[Groups(["user.read", "message.read", "message.write"])]
    #[ORM\ManyToOne(targetEntity: Chat::class, inversedBy:"messages")]
    private ?Chat $chat = null;

    #[Groups(["chat.read", "message.read"])]
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy:"messages")]
    public ?User $user = null;

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
        $chat->addMessage($this);
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        $user->addMessage($this);
        return $this;
    }

    public function isUserInMemberChat(UserInterface $user):bool
    {
        return $this->chat->getMembers()->exists(function ($key, $member) use ($user) {
            return $member->getUser() === $user;
        });
    }
}
