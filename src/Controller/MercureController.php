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

class MercureController extends AbstractController
{
    #[Route("/push", name:"mercure_push")]
    public function mercure(Request $request, HubInterface $hubInterface)
    {
        $update = new Update(
            "/login",
            json_encode(["message" => "Mercure Push"])
        );

        $hubInterface->publish($update);

        return new Response('Published!');
    }

    #[Route("/discover", name:"mercure_discover")]
    public function discover(Request $request, Discovery $discovery): JsonResponse
    {
        // Link: <https://localhost:5000/.well-known/mercure>; rel="mercure"
        $discovery->addLink($request);

        return $this->json([
            'Don123e'
        ]);
    }
}
