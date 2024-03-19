<?php

namespace App\Security;

use App\Repository\UserRepository;

use App\Interface\Security\AuthorizatedMessageProviderInterface;
use App\Interface\Security\ResponseMessageProviderInterface;
use App\Interface\Security\SecurityInterface;
use App\Service\JWTService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Abstract class providing common functionality for security controllers.
 */
abstract class AbstractSecurityController extends AbstractController implements SecurityInterface, ResponseMessageProviderInterface, AuthorizatedMessageProviderInterface 
{
    /**
     * The user repository.
     */
    protected UserRepository $userRepository;
    
    /**
     * The user password hasher interface.
     */
    protected UserPasswordHasherInterface $userPasswordHasherInterface;

    /**
     * Constructs a new AbstractSecurityController instance.
     *
     * @param UserRepository $userRepository The user repository.
     * @param UserPasswordHasherInterface $userPasswordHasherInterface The password hasher interface.
     */
    public function __construct(UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        // Set user repository and user password hasher interface
        $this->userRepository = $userRepository;
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
    }

    /**
     * Checks if the user is already authorized.
     *
     * @param Security $security The Symfony security component.
     * @return bool True if the user is authorized, otherwise false.
     */
    public function isUserAutorizated(Security $security): bool {
        return $security->isGranted('IS_AUTHENTICATED_FULLY');
    }

    /**
     * Gets the message for authorized user.
     *
     * @return string The message for authorized user.
     */
    public function getAuhorizatedMessage(): string
    {
        return "User have already autorizated";
    }
}
