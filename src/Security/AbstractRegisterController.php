<?php

namespace App\Security;

use App\Entity\User;
use App\Interface\Security\RegisterProviderInterface;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Abstract class providing common functionality for user registration controllers.
 */
abstract class AbstractRegisterController extends AbstractFormSecurityController implements RegisterProviderInterface
{
    /**
     * The entity manager interface.
     */
    private EntityManagerInterface $entityManagerInterface;

    /**
     * Constructs a new AbstractRegisterController instance.
     *
     * @param EntityManagerInterface $entityManagerInterface The entity manager interface.
     * @param UserRepository $userRepository The user repository.
     * @param UserPasswordHasherInterface $userPasswordHasherInterface The password hasher interface.
     */
    public function __construct(
        EntityManagerInterface $entityManagerInterface,
        UserRepository $userRepository,
        UserPasswordHasherInterface $userPasswordHasherInterface,
    )
    {
        // Call parent constructor
        parent::__construct($userRepository, $userPasswordHasherInterface);

        // Set entity manager interface
        $this->entityManagerInterface = $entityManagerInterface;
    }

    /**
     * Gets the full user details.
     *
     * @param User|null $user The user object.
     * @return User|null The full user details.
     */
    public function getFullUser(?User $user): ?User
    {
        // Find and return the full user details from the user repository
        return $this->userRepository->findOneBy(["email" => $user->getEmail()]);
    }

    /**
     * Gets the response based on user authentication and registration.
     *
     * @param User|null $user The user object.
     * @param Security $security The Symfony security component.
     * @return Response The response object.
     */
    public function getResponse(?User $user, Security $security): Response
    {
        // Check if the user is already authorized
        if ($this->isUserAutorizated($security)) {
            // If so, return a wrong response with authorization message
            return $this->getWrongResponse($user, $this->getAuhorizatedMessage(), $this->getAdditionalObject());
        }
        
        // Check if the user is not valid
        if (!$this->isValidResponse($user)) {
            // If not, return a wrong response with invalid credentials message
            return $this->getWrongResponse($user, $this->getWrongResponseMessage(), $this->getAdditionalObject());
        }

        // Register the user
        $this->registerUser($user);

        // Return a correct response
        return $this->getCorrectResponse($user, $this->getCorrectResponseMessage(), $this->getAdditionalObject());
    }

    /**
     * Gets the message for wrong response.
     *
     * @return string The message for wrong response.
     */
    public function getWrongResponseMessage(): string
    {
        return "User already exist under this email or phone number";
    }

    /**
     * Gets the message for correct response.
     *
     * @return string The message for correct response.
     */
    public function getCorrectResponseMessage(): string
    {
        return "Registration is correct";
    }

    /**
     * Registers a new user.
     *
     * @param User $user The user object.
     * @return void
     */
    public function registerUser(User $user): void
    {
        // Set user avatar
        $user->setAvatar("");

        // Hash user password
        $user->setPassword($this->userPasswordHasherInterface->hashPassword($user, $user->getPassword()));

        // Persist user in database
        $this->entityManagerInterface->persist($user);
        $this->entityManagerInterface->flush();
    }
}
