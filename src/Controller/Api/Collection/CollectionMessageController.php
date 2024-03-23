<?php

namespace App\Controller\Api\Collection;
use App\Entity\Message;
use App\Service\EntityManagerService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class CollectionMessageController extends AbstractController
{
    public function __construct(
        private EntityManagerService $entityManagerService,
        private Security $security
    ) { } 

    public function __invoke(): Collection
    {
        $userInt = $this->security->getUser()->getUserIdentifier();
        $user = $this->entityManagerService->getFullyUser($userInt);

        $messages = new ArrayCollection();

        $user->getMessages()->exists(function ($key, Message $message) use ($messages) {
            $messages->add($message);
        });

        return $messages;

    }
}