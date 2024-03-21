<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Controller\Api\Collection\CollectionFileController;
use App\Repository\FileRepository;

use App\Voter\FileVoter;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;

#[ApiResource(
    normalizationContext:["groups" => ["file.read"]],
    denormalizationContext:["groups" => ["file.write"]]
)]
#[GetCollection(
    security: FileVoter::IS_GRANTED_COLLECTION,
    controller: CollectionFileController::class
)]

#[Get(
    security: FileVoter::IS_GRANTED_GET,
    securityMessage:FileVoter::SECURITY_GET_MESSAGE
)]

#[Post(
    securityPostDenormalize: FileVoter::IS_GRANTED_POST,
    securityPostDenormalizeMessage: FileVoter::SECURITY_POST_MESSAGE
)]

#[Patch(
    security: FileVoter::IS_GRANTED_PATCH,
    securityMessage: FileVoter::SECURITY_PATCH_MESSAGE
)]

#[Delete(
    security: FileVoter::IS_GRANTED_DELETE,
    securityMessage: FileVoter::SECURITY_DELETE_MESSAGE
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

    #[Groups(["chat.read", "file.read"])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $format = null;

    #[Groups(["file.read", "file.write"])]
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

    public function setName(?string $name): static
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

    public function setFormat(?string $format): static
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
        $chat->addFile($this);
        return $this;
    }

    public function isUserHaveAccessToFile(UserInterface $user): bool
    {
        return $this->getChat()->getMembers()->exists(function ($key, $member) use ($user) {
            return $member->getUser() == $user;
        });
    }
}
