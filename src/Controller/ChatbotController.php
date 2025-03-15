<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ChatbotController extends AbstractController
{
    #[Route('/chatbot', name: 'app_chatbot')]
    public function index(): Response
    {
        return $this->render('chatbot/chatbot.html.twig', [
            'controller_name' => 'ChatbotController',
        ]);
    }

    private const API_URL = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent';
    private $httpClient;
    private $apiKey;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
        // In a real application, use environment variables or parameters
        $this->apiKey = 'AIzaSyDs0NKH4Eu9j1K98QjVk3aCoS1jq94S-4w';
    }

    /**
     * @Route("/api/chat", name="chat_api", methods={"POST"})
     */
    public function chatApi(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $userMessage = $data['message'] ?? '';
        
        if (empty($userMessage)) {
            return $this->json(['error' => 'Message cannot be empty'], 400);
        }
        
        try {
            // Create request body structure
            $requestBody = [
                'contents' => [
                    [
                        'parts' => [
                            [
                                'text' => $userMessage
                            ]
                        ]
                    ]
                ]
            ];

            // Make the API request
            $response = $this->httpClient->request('POST', self::API_URL . '?key=' . $this->apiKey, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => $requestBody,
            ]);

            $statusCode = $response->getStatusCode();
            $content = $response->toArray();

            if ($statusCode === 200) {
                if (isset($content['candidates'][0]['content']['parts'][0]['text'])) {
                    return $this->json(['response' => $content['candidates'][0]['content']['parts'][0]['text']]);
                }
                return $this->json(['response' => 'No response found in the API response.']);
            } else {
                return $this->json(
                    ['error' => 'HTTP error: ' . $statusCode . ' - ' . json_encode($content)], 
                    500
                );
            }

        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }
}