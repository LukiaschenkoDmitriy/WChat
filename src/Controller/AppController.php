<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    #[Route("/about", name:"app_about")]
    public function about(Request $request)
    {
        return $this->json("About");
    }

    #[Route("/privacy", name:"app_privacy")]
    public function privacy(Request $request)
    {
        return $this->json("Privacy");
    }

    #[Route("/support", name:"app_support")]
    public function support(Request $request)
    {
        return $this->json("Support");
    }
}
