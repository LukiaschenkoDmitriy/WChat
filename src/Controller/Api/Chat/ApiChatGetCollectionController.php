<?php

namespace App\Controller\Api\Chat;

use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class ApiChatGetCollectionController extends AbstractController {
    public function __construct(
        private EntityManagerInterface $entityManagerInterface,
        private Security $security
    ) { }

    public function __invoke(): Collection
    {
        $userIdentifier = $this->security->getUser()->getUserIdentifier();
        $userRepository = $this->entityManagerInterface->getRepository(User::class);
        $user = $userRepository->findOneBy(["email" => $userIdentifier]);
        

        $chats = new ArrayCollection();

        $user->getMembers()->filter(function ($member) use ($chats, $user) {
            if ($member->getUser() == $user) {
                $chat = $member->getChat();
                if (!$chats->contains($chat)) {
                    $chats->add($member->getChat());
                }
                return true;
            } return false;
        });
        
        return $chats;
    }
}