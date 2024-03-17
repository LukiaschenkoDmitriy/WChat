<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\JWTokenRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: JWTokenRepository::class)]
#[ApiResource(
    normalizationContext:["groups" => ["jwtoken.read"]],
    denormalizationContext:["groups" => ["jwtoken.write"]]
)]
class JWToken
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["user.read", "jwtoken.read"])]
    private ?int $id = null;

    #[Groups(["user.read", "jwtoken.read", "jwtoken.write"])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $jwt = null;

    #[Groups(["jwtoken.read", "jwtoken.write"])]
    #[ORM\OneToOne(targetEntity: User::class, mappedBy:"jwtoken")]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJwt(): ?string
    {
        return $this->jwt;
    }

    public function setJwt(?string $jwt): static
    {
        $this->jwt = $jwt;
        return $this;
    }

    public function getUser(): ?User 
    {
        return $this->user;
    }

    public function setUser(?User $user):static 
    {
        $this->user = $user;
        return $this;
    }
}
