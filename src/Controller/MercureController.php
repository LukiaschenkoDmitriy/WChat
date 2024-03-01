<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mercure\Discovery;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller for handling Mercure-related requests.
 */
class MercureController extends AbstractController
{
    /**
     * Discovers the Mercure hub.
     * 
     * @param Request $request The HTTP request object.
     * @param Discovery $discovery The Mercure hub discovery service.
     * @return JsonResponse The JSON response object.
     */
    #[Route("/mercure", name:"mercure")]
    public function discover(Request $request, Discovery $discovery): JsonResponse
    {
        // Add Mercure hub link to the response headers
        $discovery->addLink($request);

        // Return a JSON response with a message indicating Mercure
        return $this->json([
            'Mercure'
        ]);
    }
}
