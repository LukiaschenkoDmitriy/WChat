<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mercure\Discovery;
use Symfony\Component\Routing\Annotation\Route;

class MercureController extends AbstractController
{
    #[Route("/mercure", name:"mercure")]
    public function discover(Request $request, Discovery $discovery): JsonResponse
    {
        // Link: <https://localhost:5000/.well-known/mercure>; rel="mercure"
        $discovery->addLink($request);

        return $this->json([
            'Mercure'
        ]);
    }
}
