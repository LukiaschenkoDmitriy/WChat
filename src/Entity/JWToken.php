<?php

namespace App\Entity;

use App\Repository\JWTokenRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JWTokenRepository::class)]
class JWToken
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $jwt_token = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $user_int = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJwtToken(): ?string
    {
        return $this->jwt_token;
    }

    public function setJwtToken(?string $jwt_token): static
    {
        $this->jwt_token = $jwt_token;

        return $this;
    }

    public function getUserInt(): ?string
    {
        return $this->user_int;
    }

    public function setUserInt(?string $user_int): static
    {
        $this->user_int = $user_int;

        return $this;
    }
}
