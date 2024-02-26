<?php

namespace App\DataFixtures;

use App\Entity\Chat;
use App\Entity\Member;
use App\Entity\Message;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Fixture class for populating initial data into the database.
 */
class AppFixtures extends Fixture
{
    private EntityManagerInterface $entityManagerInterface;
    private UserPasswordHasherInterface $userPasswordHasherInterface;

    /**
     * Constructor for AppFixtures.
     * 
     * @param EntityManagerInterface $entityManagerInterface The entity manager interface.
     * @param UserPasswordHasherInterface $userPasswordHasherInterface The user password hasher interface.
     */
    public function __construct(EntityManagerInterface $entityManagerInterface, UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        $this->entityManagerInterface = $entityManagerInterface;
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
    }

    /**
     * Load method to populate data into the database.
     * 
     * @param ObjectManager $manager The object manager.
     */
    public function load(ObjectManager $manager): void
    {
        $user = (new User())
            ->setEmail("admin@gmail.com")
            ->setFirstName("Admin")
            ->setLastName("Root")
            ->setPhone("111111111")
            ->setAvatar("")
            ->setCountryNumber("48");

        $password = $this->userPasswordHasherInterface->hashPassword($user, "1111");
        $user->setPassword($password);

        $chat = (new Chat())
            ->setName("Chat")
            ->setAvatar("")
            ->setFolder("");

        $chat2 = (new Chat())
            ->setName("Chat2")
            ->setAvatar("")
            ->setFolder("");

        $member = (new Member())->setRole(1);
        $member2 = (new Member())->setRole(2);

        $user->addMember($member);
        $user->addMember($member2);
        $chat2->addMember($member2);
        $chat->addMember($member);

        $message = (new Message())
            ->setMessage("Hello world")
            ->setTime("20:22")
            ->setPinMessage("")
            ->setType(1);

        $user->addMessage($message);
        $chat->addMessage($message);
        
        $this->entityManagerInterface->persist($user);
        $this->entityManagerInterface->persist($member);
        $this->entityManagerInterface->persist($chat);
        $this->entityManagerInterface->persist($member2);
        $this->entityManagerInterface->persist($chat2);
        $this->entityManagerInterface->persist($message);
        $this->entityManagerInterface->flush();
    }
}
