<?php

namespace Api\Controller;

use Api\Service\ApiService;
use App\Repository\ChatRepository;
use App\Repository\UserRepository;
use App\Service\ConverterArrayToJson;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * ApiController is responsible for handling API requests related to chats.
 */
class ApiController extends AbstractController {
    private ApiService $apiService;
    private ChatRepository $chatRepository;
    private UserRepository $userRepository;
    
    /**
     * ApiController constructor.
     *
     * @param ApiService $apiService The API service for authentication.
     * @param UserRepository $userRepository The repository for user data.
     * @param ChatRepository $chatRepository The repository for chat data.
     */
    public function __construct(ApiService $apiService, UserRepository $userRepository, ChatRepository $chatRepository)
    {
        $this->apiService = $apiService;
        $this->userRepository = $userRepository;
        $this->chatRepository = $chatRepository;
    }

    /**
     * Retrieves the chats associated with the authenticated user.
     *
     * @param Request $request The HTTP request.
     * @return JsonResponse The JSON response containing the list of chats.
     */
    #[Route("/api/get/chats", name:"api_chat_get_chats", methods:"POST")]
    public function apiGetChats(Request $request): JsonResponse
    {
        // Execute authentication process
        $loginResponse = $this->apiService->execute($request->headers);
        // Check if authentication failed
        if ($loginResponse->getStatusCode() === Response::HTTP_UNAUTHORIZED) return $loginResponse;
        
        // Decode user data from the authentication response
        $userJson = json_decode(json_decode($loginResponse->getContent(), true), true);
        // Find the user based on the decoded user ID
        $user = $this->userRepository->findOneBy(["id" => $userJson["id"]]);
        // Retrieve chats associated with the user
        $chats = $this->chatRepository->findChatsByUser($user);

        // Convert the array of chat entities to JSON format
        $jsonData = ConverterArrayToJson::convert($chats);

        // Return JSON response with chat data
        return new JsonResponse($jsonData, Response::HTTP_OK);
    }
}
