<?php

namespace App\Controller;

use App\Entity\Chat;
use App\Entity\Message;
use App\Entity\User;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ChatController extends AbstractController
{
    private EntityManagerInterface $entityManagerInterface;

    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->entityManagerInterface = $entityManagerInterface;
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
