<?php

namespace App\Controller;

use App\Entity\ChatFile;
use App\Entity\ChatMember;
use App\Entity\ChatMessage;
use App\Entity\User;
use App\Entity\UserNotification;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChatController extends AbstractController
{
    private int $selectedChatId = 0;

    #[Route('/chat', name: 'app_chat')]
    public function index(Security $security, EntityManagerInterface $entityManagerInterface): Response
    {
        if (!$security->isGranted("IS_AUTHENTICATED_FULLY")) {
            return $this->redirectToRoute("app_login_get");
        }

        $currentUser = $entityManagerInterface->getRepository(User::class)->findOneBy(["email" => $security->getUser()->getUserIdentifier()]);

        $userMemberChats = $entityManagerInterface->getRepository(ChatMember::class)->findBy([
            "user" => $currentUser
        ]);

        $userChats = array_map(function ($memberChat) {
            return $memberChat->getChat();
        }, $userMemberChats);

        $chatMessages = $entityManagerInterface->getRepository(ChatMessage::class)->findBy([
            "chat" => $userChats[$this->selectedChatId]
        ]);

        $chatFiles = $entityManagerInterface->getRepository(ChatFile::class)->findBy([
            "chat" => $userChats[$this->selectedChatId]
        ]);

        $userNotification = $entityManagerInterface->getRepository(UserNotification::class)->findBy([
            "user" => $currentUser
        ]);

        return $this->render('chat/index.html.twig', [
            "chats" => $userChats,
            "current_chat" => $userChats[$this->selectedChatId],
            "chat_messages" => $chatMessages,
            "chat_files" => $chatFiles,
            "user_notification" => $userNotification

        ]);
    }
}
