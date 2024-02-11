<?php

namespace App\Controller;

use App\Entity\Chat;
use App\Entity\Member;
use App\Entity\Message;
use App\Entity\User;
use App\Service\ChatDirectoryManager;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
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
    public function postMessage(Request $request, #[CurrentUser()] ?User $user, Security $security)
    {
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('security_login');
        }

        return $this->redirectToRoute("chat_selector", ["id" => $request->attributes->get('id')]);
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

    private function getRoleIdOfUserInChat(User $user, Chat $chat): ?int
    {
        foreach ($chat->getMembers() as $member) {
            if ($member->getUser() == $user) {
                return $member->getRole();
            }
        }

        return null;
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
