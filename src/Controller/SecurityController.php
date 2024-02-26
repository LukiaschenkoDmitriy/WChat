<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller for handling security-related actions such as logout.
 */
class SecurityController extends AbstractController
{
    /**
     * Handles user logout.
     * 
     * @param Request $request The HTTP request object.
     * @return JsonResponse The JSON response object.
     */
    #[Route("/logout", name:"security_logout")]
    public function logout()
    {
        return $this->json("Logout");
    }
}
