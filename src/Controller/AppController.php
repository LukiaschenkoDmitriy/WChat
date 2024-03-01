<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller for handling general application routes.
 */
class AppController extends AbstractController
{
    /**
     * Renders the about page.
     * 
     * @param Request $request The HTTP request object.
     * @return Response The response object.
     */
    #[Route("/about", name:"app_about")]
    public function about(Request $request): Response
    {
        return $this->json("About");
    }

    /**
     * Renders the privacy page.
     * 
     * @param Request $request The HTTP request object.
     * @return Response The response object.
     */
    #[Route("/privacy", name:"app_privacy")]
    public function privacy(Request $request): Response
    {
        return $this->json("Privacy");
    }

    /**
     * Renders the support page.
     * 
     * @param Request $request The HTTP request object.
     * @return Response The response object.
     */
    #[Route("/support", name:"app_support")]
    public function support(Request $request): Response
    {
        return $this->json("Support");
    }
}
