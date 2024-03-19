<?php

namespace App\DataFixtures;

use App\Entity\Chat;
use App\Entity\Member;
use App\Entity\Message;
use App\Entity\User;
use App\Enum\ChatRoleEnum;
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

        $user2 = (new User())
            ->setEmail("member@gmail.com")
            ->setFirstName("Member")
            ->setLastName("1")
            ->setPhone("351111111")
            ->setAvatar("")
            ->setCountryNumber("48");

        $user3 = (new User())
            ->setEmail("root@gmail.com")
            ->setFirstName("Root")
            ->setLastName("123")
            ->setPhone("355311111")
            ->setAvatar("")
            ->setCountryNumber("48")
            ->setRoles(["ROLE_ADMIN"]);

        $user->setPassword($this->userPasswordHasherInterface->hashPassword($user, "1111"));
        $user2->setPassword($this->userPasswordHasherInterface->hashPassword($user2, "1111"));
        $user3->setPassword($this->userPasswordHasherInterface->hashPassword($user3, "1111"));

        $chat = (new Chat())
            ->setName("Chat")
            ->setAvatar("")
            ->setFolder("");

        $chat2 = (new Chat())
            ->setName("Chat2")
            ->setAvatar("")
            ->setFolder("");

        $member = (new Member())->setRole(ChatRoleEnum::MEMBER)->setUser($user)->setChat($chat);
        $member2 = (new Member())->setRole(ChatRoleEnum::ADMIN)->setUser($user)->setChat($chat2);
        $member3 = (new Member())->setRole(ChatRoleEnum::MEMBER)->setUser($user2)->setChat($chat);
        $member4 = (new Member())->setRole(ChatRoleEnum::ADMIN)->setUser($user2)->setChat($chat2);

        $message = (new Message())
            ->setMessage("Hello world")
            ->setTime("20:22")
            ->setPinMessage("")
            ->setType(1)->setUser($user)->setChat($chat);

        $message2 = (new Message())
            ->setMessage("Hello12351233 world")
            ->setTime("20:22")
            ->setPinMessage("")
            ->setType(1)->setUser($user2)->setChat($chat);

        $message3 = (new Message())
            ->setMessage("Hello123512qwerqwerqwerqwe33 world")
            ->setTime("20:22")
            ->setPinMessage("")
            ->setType(1)->setUser($user)->setChat($chat2);

        $message4 = (new Message())
            ->setMessage("Hellasdfasdo12351233 world")
            ->setTime("20:22")
            ->setPinMessage("")
            ->setType(1)->setUser($user2)->setChat($chat2);
        
        $this->entityManagerInterface->persist($user);
        $this->entityManagerInterface->persist($user2);
        $this->entityManagerInterface->persist($user3);
        $this->entityManagerInterface->persist($member);
        $this->entityManagerInterface->persist($chat);
        $this->entityManagerInterface->persist($chat2);
        $this->entityManagerInterface->persist($member2);
        $this->entityManagerInterface->persist($member3);
        $this->entityManagerInterface->persist($member4);
        $this->entityManagerInterface->persist($message);
        $this->entityManagerInterface->persist($message2);
        $this->entityManagerInterface->persist($message3);
        $this->entityManagerInterface->persist($message4);
        $this->entityManagerInterface->flush();
    }
}
