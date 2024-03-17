<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\Discovery;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
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

    #[Route("/mercure/test", name:"mercure.text")]
    public function mercureTest(Request $request, HubInterface $hubInterface): JsonResponse
    {
        $update = new Update("/chat", json_encode(["helloworld" => "message"]));

        $hubInterface->publish($update);

        return new JsonResponse("Hello world!", Response::HTTP_OK);
    }
}
