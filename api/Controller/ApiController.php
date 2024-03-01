<?php

namespace Api\Controller;

use App\Service\JWTService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ApiController extends AbstractController
{
    private JWTService $jwtService;

    public function __construct(JWTService $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    #[Route("/api/get/chats", name:"api_get_chats", methods:"POST")]
    public function apiGetChats(Request $request) 
    {
        $response = $this->getResponseIfTokenNotValidate($request);
        if ($response != null) return $response;
        
        return new JsonResponse("chats");
    }

    public function getResponseIfTokenNotValidate(Request $request): ?Response
    {
        if ($request->request->get("jwt") == null) return new JsonResponse("Invalid parameters in request body", Response::HTTP_UNAUTHORIZED);
        if (!$this->checkAndValidateJwToken($request->request)) return new JsonResponse("Invalid JWT token", Response::HTTP_UNAUTHORIZED);

        $token = $this->jwtService->decodeToken($request->request->get("jwt"));
        if ($token == null) return new JsonResponse("The token is expired, create a new token using /api/login.");

        return null;
    }

    public function checkAndValidateJwToken(InputBag $inputBag): bool {
        return $this->jwtService->existToken($inputBag->get("jwt"));
    }
}