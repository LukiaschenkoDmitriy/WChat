<?php

namespace App\Controller\Security;

use App\Repository\UserRepository;

use App\Interface\Security\AuthorizatedMessageProviderInterface;
use App\Interface\Security\ResponseMessageProviderInterface;
use App\Interface\Security\SecurityInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

abstract class AbstractSecurityController extends AbstractController 
implements SecurityInterface, ResponseMessageProviderInterface, AuthorizatedMessageProviderInterface 
{
    protected UserRepository $userRepository;
    protected UserPasswordHasherInterface $userPasswordHasherInterface;

    public function __construct(UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        $this->userRepository = $userRepository;
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
    }

    public function isUserAutorizated(Security $security): bool {
        return $security->isGranted('IS_AUTHENTICATED_FULLY');
    }

    public function getAuhorizatedMessage(): string
    {
        return "User have already autorizated";
    }
}