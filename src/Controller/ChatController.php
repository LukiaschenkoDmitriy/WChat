<?php

namespace App\Controller;

use App\Entity\Chat;
use App\Entity\Member;
use App\Entity\Message;
use App\Entity\User;
use App\Service\ChatDirectoryManager;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ChatController extends AbstractController
{
    private EntityManagerInterface $entityManagerInterface;
    private ChatDirectoryManager $directoryManager;

    public function __construct(EntityManagerInterface $entityManagerInterface, ChatDirectoryManager $directoryManager)
    {
        $this->entityManagerInterface = $entityManagerInterface;
        $this->directoryManager = $directoryManager;
    }

    #[Route("/chat", name:"chat")]
    public function chat(Request $request, #[CurrentUser()] ?User $user, Security $security)
    {
        // Redirect to login page if user is not fully authenticated
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('security_login');
        }

        // Render chat index page with user's members
        return $this->render("/chat/index.html.twig", [
            'members' => $user->getMembers()
        ]);
    }

    #[Route("/chat/create-chat", name:"chat_create_chat", methods:"POST")]
    public function createChat(Request $request, #[CurrentUser()] ?User $user, Security $security)
    {
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('security_login');
        }

        // Getting the chat name and the uploaded image from the request
        $chatName = $request->request->get("create_chat_name");
        $img = $request->files->get("create_avatar");

        // Creating a new Chat instance with the provided name
        $newChat = (new Chat())->setName($chatName);

        // Getting the root path for the chat directory
        // Generating a unique folder name for the chat
        $rootPath = $this->getParameter("chat_directory");
        $folderName = $this->directoryManager->getUniqueChatFolderName();

        // Constructing the absolute path for the chat folder
        // Constructing the relative path for the chat folder
        $absolutePath = $rootPath . "/" . $folderName;
        $relativePath = "chats/" . $folderName;

        // Creating the folder for the chat
        $this->directoryManager->createFolderChat($absolutePath);

        // Setting the folder path for the new chat
        $newChat->setFolder($relativePath);

        // If an image was uploaded, save it to the chat folder and set the avatar path
        if ($img instanceof UploadedFile) {
            $allowedExtensions = ['jpeg', 'jpg', 'png', 'gif'];
            if (in_array($img->guessExtension(), $allowedExtensions)) {
                $this->directoryManager->saveAvatarInDirectory($absolutePath, $img);
                $newChat->setAvatar($relativePath . "/avatar." . $img->guessExtension());
            }
        }

        // Creating a new Member with role 2 and associating it with the user and the chat
        $ownMember = (new Member())->setRole(2);
        $user->addMember($ownMember);
        $newChat->addMember($ownMember);

        // Persisting the member and the new chat to the database
        $this->entityManagerInterface->persist($ownMember);
        $this->entityManagerInterface->persist($newChat);
        $this->entityManagerInterface->flush();

        // Redirecting to the chat selector page with the ID of the new chat
        return $this->redirectToRoute("chat_selector", ["id" => $newChat->getId()]);

    }

        #[Route("/chat/{id}/post-message", name:"chat_post_message", methods:"POST")]
        public function postMessage(Request $request, #[CurrentUser()] ?User $user, Security $security, HubInterface $hubInterface)
        {
            if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
                return $this->redirectToRoute('security_login');
            }

            $message = $request->request->get("message");
            $chatId = $request->attributes->get("id");

            $sChat = $this->getChatIfUserIsMember($user, $chatId);
            if (!$sChat) return $this->redirectToRoute("chat");

            $dataTime = new DateTime();

            $newMessage = (new Message())->setMessage($message)->setTime($dataTime->format('H:i'));
            $user->addMessage($newMessage);
            $sChat->addMessage($newMessage);

            $this->entityManagerInterface->persist($newMessage);
            $this->entityManagerInterface->flush();

            $tenMessages = $this->convertMessagesToJson($this->entityManagerInterface->getRepository(Message::class)->findLastTenMessages());

            $update = new Update(
                '/chat/' . $chatId,
                $tenMessages
            );
            $hubInterface->publish($update);

            return new JsonResponse(["status" => "201"]);
        }

    #[Route("/chat/{id}", name:"chat_selector")]
    public function chatSelector(Request $request, #[CurrentUser()] ?User $user, Security $security)
    {
        // Redirect to login page if user is not fully authenticated
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('security_login');
        }

        // Get chat ID from the request attributes
        $chatId = $request->attributes->get('id');

        // Redirect to the previous page if the user is not a member of the selected chat
        $sChat = $this->getChatIfUserIsMember($user, $chatId);
        if (!$sChat) return $this->redirectToRoute("chat");

        $roleId = $this->getRoleIdOfUserInChat($user, $sChat);

        // Render chat index page with user's members and the selected chat
        return $this->render("/chat/index.html.twig", [
            'members' => $user->getMembers(),
            "sChat" => $sChat,
            "roleId" => $roleId
        ]);
    }

    #[Route("/chat/{id}/change-name", name:"chat_change_name", methods:"POST")]
    public function changeName(Request $request, #[CurrentUser()] ?User $user, Security $security)
    {
        // Redirect to login page if user is not fully authenticated
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('security_login');
        }

        // Get chat ID from the request attributes
        $chatId = $request->attributes->get('id');

        // Redirect to the previous page if the user is not a member of the selected chat
        $sChat = $this->getChatIfUserIsMember($user, $chatId);
        if (!$sChat) return $this->redirectToRoute("chat");

        $roleId = $this->getRoleIdOfUserInChat($user, $sChat);

        if ($roleId < 2) return $this->redirectToRoute("chat_selector", ["id" => $chatId]);

        $sChat->setName($request->request->get("new_name"));

        $this->entityManagerInterface->persist($sChat);
        $this->entityManagerInterface->flush();

        // Render chat index page with user's members and the selected chat
        return $this->redirectToRoute("chat_selector", ["id" => $chatId]);
    }

    #[Route("/chat/{id}/change-avatar", name:"chat_change_avatar", methods:"POST")]
    public function changeAvatar(Request $request, #[CurrentUser()] ?User $user, Security $security)
    {
        // Redirect to login page if user is not fully authenticated
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('security_login');
        }

        // Get chat ID from the request attributes
        $chatId = $request->attributes->get('id');

        // Redirect to the previous page if the user is not a member of the selected chat
        $sChat = $this->getChatIfUserIsMember($user, $chatId);
        if (!$sChat) return $this->redirectToRoute("chat");

        $roleId = $this->getRoleIdOfUserInChat($user, $sChat);

        if ($roleId < 2) return $this->redirectToRoute("chat_selector", ["id" => $chatId]);

        $img = $request->files->get("new_avatar");
        if ($img instanceof UploadedFile) {
            $allowedExtensions = ['jpeg', 'jpg', 'png', 'gif'];
            if (in_array($img->guessExtension(), $allowedExtensions)) {
                $relativePath = $sChat->getFolder();
                $absolutePath = $this->getParameter("public_directory")."/".$relativePath;
                
                $this->directoryManager->saveAvatarInDirectory($absolutePath, $img);
                $sChat->setAvatar($relativePath . "/avatar." . $img->guessExtension());

                $this->entityManagerInterface->persist($sChat);
                $this->entityManagerInterface->flush();
            }
        }

        // Render chat index page with user's members and the selected chat
        return $this->redirectToRoute("chat_selector", ["id" => $chatId]);
    }

    #[Route("/chat/{id}/delete-chat", name:"chat_delete_chat", methods:"POST")]
    public function deleteChat(Request $request, #[CurrentUser()] ?User $user, Security $security)
    {
        // Redirect to login page if user is not fully authenticated
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('security_login');
        }

        // Get chat ID from the request attributes
        $chatId = $request->attributes->get('id');

        // Redirect to the previous page if the user is not a member of the selected chat
        $sChat = $this->getChatIfUserIsMember($user, $chatId);
        if (!$sChat) return $this->redirectToRoute("chat");

        $roleId = $this->getRoleIdOfUserInChat($user, $sChat);

        if ($roleId < 2) return $this->redirectToRoute("chat_selector", ["id" => $chatId]);

        $this->entityManagerInterface->remove($sChat);
        $this->entityManagerInterface->flush();

        // Render chat index page with user's members and the selected chat
        return $this->redirectToRoute("chat_selector", ["id" => $chatId]);
    }

    #[Route("/chat/{id}/add-member", name:"chat_add_member", methods:"POST")]
    public function addMember(Request $request, #[CurrentUser()] ?User $user, Security $security)
    {
        // Redirect to login page if user is not fully authenticated
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('security_login');
        }

        // Get chat ID from the request attributes
        $chatId = $request->attributes->get('id');

        // Redirect to the previous page if the user is not a member of the selected chat
        $sChat = $this->getChatIfUserIsMember($user, $chatId);
        if (!$sChat) return $this->redirectToRoute("chat");

        $roleId = $this->getRoleIdOfUserInChat($user, $sChat);

        if ($roleId < 1) return $this->redirectToRoute("chat_selector", ["id" => $chatId]);

        $user_id = $request->request->get("user_id");
        $role_id = $request->request->get("role_id");

        $existUser = $this->entityManagerInterface->getRepository(User::class)->findOneBy(["id" => $user_id]);
        if ($existUser == null || ((int)$role_id > 2 || (int)$role_id < 0)) return $this->redirectToRoute("chat_selector", ["id" => $chatId]);

        $member = (new Member())->setRole((int)$role_id);

        $sChat->addMember($member);
        $existUser->addMember($member);

        $this->entityManagerInterface->persist($member);
        $this->entityManagerInterface->flush();
        
        // Render chat index page with user's members and the selected chat
        return $this->redirectToRoute("chat_selector", ["id" => $chatId]);
    }

    #[Route("/chat/{id}/remove-member", name:"chat_remove_member", methods:"POST")]
    public function removeMember(Request $request, #[CurrentUser()] ?User $user, Security $security)
    {
        // Redirect to login page if user is not fully authenticated
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('security_login');
        }

        // Get chat ID from the request attributes
        $chatId = $request->attributes->get('id');

        // Redirect to the previous page if the user is not a member of the selected chat
        $sChat = $this->getChatIfUserIsMember($user, $chatId);
        if (!$sChat) return $this->redirectToRoute("chat");

        $roleId = $this->getRoleIdOfUserInChat($user, $sChat);

        if ($roleId < 1) return $this->redirectToRoute("chat_selector", ["id" => $chatId]);

        $user_id = $request->request->get("user_id");

        $existUser = $this->entityManagerInterface->getRepository(User::class)->findOneBy(["id" => $user_id]);
        if ($existUser == null) return $this->redirectToRoute("chat_selector", ["id" => $chatId]);

        $member = $this->entityManagerInterface->getRepository(Member::class)->findOneBy(["user" => $existUser, "chat" => $sChat]);
        if ($member == null) return $this->redirectToRoute("chat_selector", ["id" => $chatId]);

        $existUser->removeMember($member);
        $sChat->removeMember($member);

        $this->entityManagerInterface->flush();
        
        // Render chat index page with user's members and the selected chat
        return $this->redirectToRoute("chat_selector", ["id" => $chatId]);
    }

    #[Route("/chat/{id}/set-role", name:"chat_set_role", methods:"POST")]
    public function setRole(Request $request, #[CurrentUser()] ?User $user, Security $security)
    {
        // Redirect to login page if user is not fully authenticated
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('security_login');
        }

        // Get chat ID from the request attributes
        $chatId = $request->attributes->get('id');

        // Redirect to the previous page if the user is not a member of the selected chat
        $sChat = $this->getChatIfUserIsMember($user, $chatId);
        if (!$sChat) return $this->redirectToRoute("chat");

        $roleId = $this->getRoleIdOfUserInChat($user, $sChat);

        if ($roleId < 1) return $this->redirectToRoute("chat_selector", ["id" => $chatId]);

        $user_id = $request->request->get("user_id");
        $role_id = $request->request->get("role_id");

        $existUser = $this->entityManagerInterface->getRepository(User::class)->findOneBy(["id" => $user_id]);
        if ($existUser == null || ((int)$role_id > 2 || (int)$role_id < 0)) return $this->redirectToRoute("chat_selector", ["id" => $chatId]);

        $member = $this->entityManagerInterface->getRepository(Member::class)->findOneBy(["user" => $existUser, "chat" => $sChat]);
        if ($member == null) return $this->redirectToRoute("chat_selector", ["id" => $chatId]);

        $member->setRole((int)$role_id);
        $this->entityManagerInterface->persist($member);
        $this->entityManagerInterface->flush();
        
        // Render chat index page with user's members and the selected chat
        return $this->redirectToRoute("chat_selector", ["id" => $chatId]);
    }

    private function getRoleIdOfUserInChat(User $user, Chat $chat): ?int
    {
        foreach ($chat->getMembers() as $member) {
            if ($member->getUser() == $user) {
                return $member->getRole();
            }
        }

        return null;
    }

    private function convertMessagesToJson(array $messages): string {
        $json = [];

        foreach ($messages as $message) {
            $json[] = $message->toJson();
        }

        return json_encode($json);
    }

    private function getChatIfUserIsMember(User $user, int $chatId): ?Chat
    {
        // Check if the user is a member of the selected chat
        foreach ($user->getMembers() as $member) {
            if ($member->getChat()->getId() == $chatId) {
                return $member->getChat();
            }
        }

        return null;
    }
}
