<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AboutController extends AbstractController
{
    #[Route('/about', name: 'app_about')]
    public function index(Security $security): Response
    {
        if ($security->getUser() != null) {
            $security->logout(false);
        }

        return $this->render('about/index.html.twig', [
            'controller_name' => 'AboutController',
        ]);
    }
}
