<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ApiController extends AbstractController {
    #[Route(path:"/api/auth", name:"api_auth", methods:["POST"])]
    public function apiAuth(Request $request):Response {
        return new JsonResponse();
    }
}