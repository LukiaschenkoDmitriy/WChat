<?php

namespace App\Controller\Api\Collection;

use App\Entity\User;
use App\Service\EntityManagerService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class CollectionChatController extends AbstractController {
    public function __construct(
        private EntityManagerService $entityManagerService,
        private EntityManagerInterface $entityManagerInterface,
        private Security $security
    ) { }

    public function __invoke(): Collection
    {
        $userIdentifier = $this->security->getUser()->getUserIdentifier();
        $user = $this->entityManagerService->getFullyUser($userIdentifier);
        

        $chats = new ArrayCollection();

        $user->getMembers()->filter(function ($member) use ($chats, $user) {
            if ($member->getUser() == $user) {
                $chats->add($member->getChat());
            }
        });
        
        return $chats;
    }
}