<?php

namespace App\Controller;

use App\Service\ConvertEntityService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller for rendering React application.
 */
class ReactController extends AbstractController
{
    #[Route('/app/{reactRouting}', name: 'app_react', requirements: ['reactRouting' => '^(?!api).+'], defaults: ['reactRouting' => null])]
    public function index(Security $security)
    {
        if (!$security->isGranted("ROLE_USER")) return $this->redirectToRoute("security_login");

        return $this->render("/react/index.html.twig", [
            "user" => $security->getUser()
        ]);
    }
}
