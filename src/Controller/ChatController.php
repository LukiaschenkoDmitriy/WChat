<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChatController extends AbstractController
{
    #[Route('/chat', name: 'app_chat')]
    public function index(Security $security): Response
    {
        if ($security->getUser() == null) {
            return $this->redirectToRoute("app_login");
        }

        return $this->render('chat/index.html.twig', [
            'controller_name' => 'ChatController',
        ]);
    }
}
