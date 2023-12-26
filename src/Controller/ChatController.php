<?php

namespace App\Controller;

use App\Entity\Chat;
use App\Entity\ChatFile;
use App\Entity\ChatMember;
use App\Entity\ChatMessage;
use App\Entity\User;
use App\Entity\UserNotification;
use App\Service\RoleManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChatController extends AbstractController
{
    private Security $security;
    private EntityManagerInterface $entityManagerInterface; 
    private RoleManager $roleManager;

    public function __construct(Security $security, RoleManager $roleManager, EntityManagerInterface $entityManagerInterface) {
        $this->security = $security;
        $this->entityManagerInterface = $entityManagerInterface;
        $this->roleManager = $roleManager;
    }



    #[Route("chat/s/remove_member", name:"app_chat_settings_remove_member", methods:"POST")]
    public function settingsRemoveMember(Request $request): Response {
        if (!$this->security->isGranted("IS_AUTHENTICATED_FULLY")) {
            return $this->redirectToRoute("app_login_get");
        }

        $currentUser = $this->getUserByEmail($this->security->getUser()->getUserIdentifier());

        if (!$this->roleManager->hasUserRole($currentUser->getLastSelectedChat(), $currentUser, 2)) {
            return $this->redirectToRoute("app_chat", ["NotPermissions"]);
        }

        $removeUser = $this->getUserByEmail($request->request->get("delete_member"));
        if ($removeUser == null) return $this->redirectToRoute("app_chat", ["UserNotExist"]);

        $chatMemberOfUser = $this->entityManagerInterface->getRepository(ChatMember::class)->findOneBy([
            "user" => $removeUser,
            "chat" => $currentUser->getLastSelectedChat()
        ]);

        if ($chatMemberOfUser == null) return $this->redirectToRoute("app_chat", ["UserNotInChat"]);


        $removeUser->setLastSelectedChat(null);
        $this->entityManagerInterface->remove($chatMemberOfUser);
        $this->entityManagerInterface->flush();

        return $this->redirectToRoute("app_chat", ["UserWillBeRemoved"]);
    }

    #[Route("chat/s/add_member", name:"app_chat_settings_add_member", methods:"POST")]
    public function settingsAddMember(Request $request): Response {
        if (!$this->security->isGranted("IS_AUTHENTICATED_FULLY")) {
            return $this->redirectToRoute("app_login_get");
        }

        $currentUser = $this->getUserByEmail($this->security->getUser()->getUserIdentifier());

        if (!$this->roleManager->hasUserRole($currentUser->getLastSelectedChat(), $currentUser, 2)) {
            return $this->redirectToRoute("app_chat", ["NotPermissions"]);
        }

        $newUser = $this->getUserByEmail($request->request->get("add_member"));
        if ($newUser == null) return $this->redirectToRoute("app_chat", ["UserNotExist"]);

        $chatMemberOfUser = $this->entityManagerInterface->getRepository(ChatMember::class)->findOneBy([
            "user" => $newUser,
            "chat" => $currentUser->getLastSelectedChat()
        ]);

        if ($chatMemberOfUser != null) return $this->redirectToRoute("app_chat", ["UserAlreadyInChat"]);

        $newChatMember = new ChatMember();
        $newChatMember
            ->setChat($currentUser->getLastSelectedChat())
            ->setRoleId(0)
            ->setUser($newUser);

        $this->entityManagerInterface->persist($newChatMember);
        $this->entityManagerInterface->flush();

        return $this->redirectToRoute("app_chat", ["UserWillBeAdd"]);
    }

    #[Route("chat/s/change_role", name:"app_chat_settings_change_role", methods:"POST")]
    public function settingsChangeRole(Request $request): Response {
        if (!$this->security->isGranted("IS_AUTHENTICATED_FULLY")) {
            return $this->redirectToRoute("app_login_get");
        }

        $currentUser = $this->getUserByEmail($this->security->getUser()->getUserIdentifier());

        if (!$this->roleManager->hasUserRole($currentUser->getLastSelectedChat(), $currentUser, 3)) {
            return $this->redirectToRoute("app_chat", ["NotPermissions"]);
        }

        $user = $request->request->get("user");
        $role_id = (int)$request->request->get("member_role");

        $user = $this->getUserByEmail($user);

        if ($user == null) return $this->redirectToRoute("app_chat", ["UserNotExist"]);

        $chatMemberOfUser = $this->entityManagerInterface->getRepository(ChatMember::class)->findOneBy([
            "user" => $user,
            "chat" => $currentUser->getLastSelectedChat()
        ]);

        if ($chatMemberOfUser == null) return $this->redirectToRoute("app_chat", ["UserNotInChat"]);

        $chatMemberOfUser->setRoleId($role_id);
        $this->entityManagerInterface->flush();

        return $this->redirectToRoute("app_chat", ["UserRoleWillBeChanged"]);
    }

    #[Route("chat/s/change-name", name:"app_chat_settings_change_name", methods:"POST")]
    public function settingsChangeName(Request $request): Response {
        if (!$this->security->isGranted("IS_AUTHENTICATED_FULLY")) {
            return $this->redirectToRoute("app_login_get");
        }

        $currentUser = $this->getUserByEmail($this->security->getUser()->getUserIdentifier());

        if (!$this->roleManager->hasUserRole($currentUser->getLastSelectedChat(), $currentUser, 3)) {
            return $this->redirectToRoute("app_chat", ["NotPermissions"]);
        }

        $newName = $request->request->get("group_name");
        $chat = $currentUser->getLastSelectedChat();

        $chat->setName($newName);
        $this->entityManagerInterface->flush();

        return $this->redirectToRoute("app_chat", ["GroupNameWillBeChanged"]);
    }

    #[Route("/chat/s/change-photo", name: "app_chat_settings_change_photo", methods:"POST")]
    public function settingsChangePhoto():Response {
        if (!$this->security->isGranted("IS_AUTHENTICATED_FULLY")) {
            return $this->redirectToRoute("app_login_get");
        }

        $currentUser = $this->getUserByEmail($this->security->getUser()->getUserIdentifier());

        if (!$this->roleManager->hasUserRole($currentUser->getLastSelectedChat(), $currentUser, 3)) {
            return $this->redirectToRoute("app_chat", ["NotPermissions" => true]);
        }

        $targetDirectory = $_SERVER['DOCUMENT_ROOT']. "/chat_images/";
        $targetFile = $targetDirectory.basename($_FILES["image"]["name"]);

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {

            $currentChat = $currentUser->getLastSelectedChat();
            $currentChat->setImage("chat_images/".$_FILES["image"]["name"]);
            $this->entityManagerInterface->flush();

            return $this->redirectToRoute("app_chat", ["GroupImageWillBeChanged"]);
        } else {
            return $this->redirectToRoute("app_chat");
        }
    }

    #[Route("/chat/sw", name: "app_chat_switch", methods:"POST")]
    public function switchChat(Request $requests): Response {
        if (!$this->security->isGranted("IS_AUTHENTICATED_FULLY")) {
            return $this->redirectToRoute("app_login_get");
        }

        $chatId = $requests->request->get("_chat_id");

        $currentUser = $this->getUserByEmail($this->security->getUser()->getUserIdentifier());
        $selectedChat = $this->entityManagerInterface->getRepository(Chat::class)->findOneBy(["id" => $chatId]);
        $currentUser->setLastSelectedChat($selectedChat);
        $this->entityManagerInterface->flush();

        return $this->redirectToRoute("app_chat");
    }

    #[Route("/chat/send", name: "app_chat_send_message", methods:"POST")]
    public function sendMessage(Request $requests): Response {
        if (!$this->security->isGranted("IS_AUTHENTICATED_FULLY")) {
            return $this->redirectToRoute("app_login_get");
        }

        $message = $requests->request->get("_message");
        $currentUser = $this->getUserByEmail($this->security->getUser()->getUserIdentifier());

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

        $currentUser = $this->getUserByEmail($this->security->getUser()->getUserIdentifier());
        $userChats = $this->getUserChats($currentUser);

        $chatMessages = null;
        $chatFiles = null;
        $userMemberInfo = null;
        $selectedChat = $currentUser->getLastSelectedChat();
        if (!empty($userChats) && $selectedChat) {
            $chatMessages = $this->getChatMessages($selectedChat);
            $chatFiles = $this->getChatFiles($selectedChat);
            $userMemberInfo = $this->entityManagerInterface->getRepository(ChatMember::class)->findOneBy(["chat" => $selectedChat, "user" => $currentUser]);
        }

        $userNotification = $this->getChatNotificationsForUser($currentUser);

        return $this->render('chat/index.html.twig', [
            "user_info" => $userMemberInfo,
            "chats" => $userChats,
            "current_chat" => $selectedChat,
            "chat_messages" => $chatMessages,
            "chat_files" => $chatFiles,
            "user_notification" => $userNotification
        ]);
    }

    private function getUserByEmail(string $email): User | null {
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
