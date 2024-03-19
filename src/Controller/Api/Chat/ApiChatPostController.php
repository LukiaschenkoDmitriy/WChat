<?php

namespace App\Controller\Api\Chat;

use App\Entity\Chat;
use App\Entity\Member;
use App\Enum\ChatRoleEnum;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class ApiChatPostController extends AbstractController{
    public function __construct(
        private EntityManagerInterface $entityManagerInterface,
        private Security $security
    ) { }

    public function __invoke(Chat $chat): Chat
    {
        $currentUser = $this->security->getUser();

        $ownerMember = (new Member())
            ->setRole(ChatRoleEnum::OWNER)
            ->setUser($currentUser)
            ->setChat($chat);

        $this->entityManagerInterface->persist($ownerMember);
        $this->entityManagerInterface->persist($chat);
        $this->entityManagerInterface->flush();

        return $chat;
    }
}