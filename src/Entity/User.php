<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\UserRepository;

use App\Voter\UserVoter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ApiResource(
    normalizationContext:["groups" => ["user.read"]],
    denormalizationContext:["groups" => ["user.write"]],
)]
#[GetCollection(
    security: UserVoter::IS_GRANTED_COLLECTION,
    securityMessage: UserVoter::SECURITY_COLLECTION_MESSAGE
)]
#[Get(
    security: UserVoter::IS_GRANTED_GET,
    securityMessage: UserVoter::SECURITY_GET_MESSAGE
)]
#[Post(
    securityPostDenormalize: UserVoter::IS_GRANTED_POST,
    securityPostDenormalizeMessage: UserVoter::SECURITY_POST_MESSAGE
)]
#[Patch(
    security: UserVoter::IS_GRANTED_PATCH,
    securityPostDenormalizeMessage: UserVoter::SECURITY_PATCH_MESSAGE
)]
#[Delete(
    security: UserVoter::IS_GRANTED_DELETE,
    securityMessage: UserVoter::SECURITY_DELETE_MESSAGE
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["chat.read", 'member.read', "message.read", "user.read"])]
    private int $id;

    #[Groups(["chat.read", 'member.read', "message.read", "user.read", "user.write"])]
    #[Assert\NotBlank]
    #[ORM\Column(length: 180, unique: true)]
    private string $email;

    #[Groups(["chat.read", 'member.read', "message.read", "user.read", "user.write"])]
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[Groups(["user.read", "user.write"])]
    #[Assert\NotBlank]
    #[ORM\Column]
    private string $password;

    #[Groups(["chat.read", 'member.read', "message.read", "user.read", "user.write"])]
    #[ORM\Column]
    private string $firstName;

    #[Groups(["chat.read", 'member.read', "message.read", "user.read", "user.write"])]
    #[ORM\Column]
    private string $lastName;

    #[ORM\Column]
    private bool $verified = false;

    #[Groups(["chat.read", 'member.read', "message.read", "user.read", "user.write"])]
    #[ORM\Column]
    private string $phone;

    #[Groups(["chat.read", 'member.read', "message.read", "user.read", "user.write"])]
    #[ORM\Column]
    private string $countryNumber;

    #[Groups(["chat.read", 'member.read', "message.read", "user.read", "user.write"])]
    #[ORM\Column]
    private ?string $avatar = null;

    #[Groups(["user.read"])]
    #[ORM\OneToMany(targetEntity: Member::class, mappedBy:"user", cascade: ["remove"])]
    private Collection $members;

    #[Groups(["user.read"])]
    #[ORM\OneToMany(targetEntity: Message::class, mappedBy:"user", cascade: ["remove"])]
    private Collection $messages;

    public function __construct()
    {
        $this->members = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

	public function getFirstName() : string 
    {
		return $this->firstName;
	}

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): string 
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function isVerified(): bool
    {
        return $this->verified;
    }

    public function setVerified(bool $verified): static
    {
        $this->verified = $verified;
        return $this;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;
        return $this;
    }

    public function getCountryNumber(): string
    {
        return $this->countryNumber;
    }

    public function setCountryNumber(string $countryNumber): static
    {
        $this->countryNumber = $countryNumber;
        return $this;
    }

    public function getFullPhone(): string
    {
        return $this->getCountryNumber().$this->getPhone();
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

    public function addMember(Member $member): static
    {
        if (!$this->members->contains($member)) {
            $this->members[] = $member;
            $member->setUser($this);
        }

        return $this;
    }

    public function removeMember(Member $member): static
    {
        if ($this->members->removeElement($member)) {
            if ($member->getUser() === $this) {
                $member->setUser(null);
            }
        }

        return $this;
    }

    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setUser($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            if ($message->getUser() === $this) {
                $message->setUser(null);
            }
        }

        return $this;
    }
}