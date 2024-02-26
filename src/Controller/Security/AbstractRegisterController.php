<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Interface\Security\RegisterProviderInterface;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

abstract class AbstractRegisterController extends AbstractFormSecurityController implements RegisterProviderInterface
{
    private EntityManagerInterface $entityManagerInterface;

    public function __construct(
        EntityManagerInterface $entityManagerInterface,
        UserRepository $userRepository,
        UserPasswordHasherInterface $userPasswordHasherInterface
    )
    {
        $this->userRepository = $userRepository;
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
        $this->entityManagerInterface = $entityManagerInterface;
    }

    public function getFullUser(?User $user): ?User
    {
        return $this->userRepository->findOneBy(["email" => $user->getEmail()]);
    }

    public function getResponse(?User $user, Security $security): Response
    {
        if ($this->isUserAutorizated($security)) return $this->getWrongResponse($user, $this->getAuhorizatedMessage(), $this->getAdditionalObject());
        if (!$this->isValidResponse($user)) return $this->getWrongResponse($user, $this->getWrongResponseMessage(), $this->getAdditionalObject());

        $this->registerUser($user);

        return $this->getCorrectResponse($user, $this->getCorrectResponseMessage(), $this->getAdditionalObject());
    }

    public function getWrongResponseMessage(): string
    {
        return "User already exist under this email or phone number";
    }

    public function getCorrectResponseMessage(): string
    {
        return "Registration is correct";
    }

    public function registerUser(User $user): void
    {
        $user->setAvatar("");
        $user->setPassword($this->userPasswordHasherInterface->hashPassword($user, $user->getPassword()));

        $this->entityManagerInterface->persist($user);
        $this->entityManagerInterface->flush();
    }
}