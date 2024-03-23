<?php

namespace App\Controller\Api\Get\Custom;
use App\Entity\Chat;
use App\Entity\Message;
use App\Service\EntityManagerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class GetLastMessageChatController extends AbstractController {
    public function __construct(
        private EntityManagerService $entityManagerService,
        private Security $security
    ) { } 

    public function __invoke(Chat $chat): Message
    {
        return $chat->getLastMessage();
    }
}