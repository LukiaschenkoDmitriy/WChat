<?php

namespace App\Controller;

use App\Entity\Chat;
use App\Entity\ChatFile;
use App\Entity\ChatMember;
use App\Entity\ChatMessage;
use App\Entity\User;
use App\Entity\UserNotification;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChatController extends AbstractController
{
    private Security $security;
    private EntityManagerInterface $entityManagerInterface;

    public function __construct(Security $security, EntityManagerInterface $entityManagerInterface) {
        $this->security = $security;
        $this->entityManagerInterface = $entityManagerInterface;
    }

    #[Route("/chat/switch", name: "app_chat_switch", methods:"POST")]
    public function switchChat(Request $requests): Response {
        $chatId = $requests->request->get("_chat_id");

        $currentUser = $this->getCurrentUser($this->security->getUser()->getUserIdentifier());
        $selectedChat = $this->entityManagerInterface->getRepository(Chat::class)->findOneBy(["id" => $chatId]);
        $currentUser->setLastSelectedChat($selectedChat);
        $this->entityManagerInterface->flush();

        return $this->redirectToRoute("app_chat");
    }

    #[Route("/chat/send", name: "app_chat_send_message", methods:"POST")]
    public function sendMessage(Request $requests): Response {
        $message = $requests->request->get("_message");
        $currentUser = $this->getCurrentUser($this->security->getUser()->getUserIdentifier());

        $newMessage = new ChatMessage();
        $newMessage
            ->setChat($currentUser->getLastSelectedChat())     
            ->setUser($currentUser)
            ->setDate(strval(date("h:i")))
            ->setMessage($message);

        $this->entityManagerInterface->persist($newMessage);
        $this->entityManagerInterface->flush();

        return $this->redirectToRoute("app_chat");
    }

    #[Route('/chat', name: 'app_chat')]
    public function index(): Response
    {
        if (!$this->security->isGranted("IS_AUTHENTICATED_FULLY")) {
            return $this->redirectToRoute("app_login_get");
        }

        $currentUser = $this->getCurrentUser($this->security->getUser()->getUserIdentifier());
        $userChats = $this->getUserChats($currentUser);

        $chatMessages = null;
        $chatFiles = null;
        $selectedChat = $currentUser->getLastSelectedChat();
        if (!empty($userChats) && $selectedChat) {
            $chatMessages = $this->getChatMessages($selectedChat);
            $chatFiles = $this->getChatFiles($selectedChat);
        }

        $userNotification = $this->getChatNotificationsForUser($currentUser);

        return $this->render('chat/index.html.twig', [
            "chats" => $userChats,
            "current_chat" => $selectedChat,
            "chat_messages" => $chatMessages,
            "chat_files" => $chatFiles,
            "user_notification" => $userNotification
        ]);
    }

    private function getCurrentUser(string $email): User | null {
        return $this->entityManagerInterface->getRepository(User::class)->findOneBy(["email" => $email]);
    }

    private function getUserChats(User $user): array {
        $userChatsAsMember = $this->entityManagerInterface->getRepository(ChatMember::class)->findBy([
            "user" => $user
        ]);

        return array_map(function ($memberChat) {
            return $memberChat->getChat();
        }, $userChatsAsMember);
    }

    private function getChatMessages(Chat $chat): array {
        return $this->entityManagerInterface->getRepository(ChatMessage::class)->findBy([
            "chat" => $chat
        ]);
    }

    private function getChatFiles(Chat $chat): array {
        return $this->entityManagerInterface->getRepository(ChatFile::class)->findBy([
            "chat" => $chat
        ]);
    }

    private function getChatNotificationsForUser(User $user): array {
        return $this->entityManagerInterface->getRepository(UserNotification::class)->findBy([
            "user" => $user
        ]);
    }
}
