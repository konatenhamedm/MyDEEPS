<?php

namespace App\Controller;

use App\Service\SendMailService;
use OpenApi\Attributes as OA;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AuthController extends AbstractController
{
    #[Route('/api/login_check', name: 'api_login_check', methods: ['POST'])]
    #[OA\Post(
        summary: "Authentification utilisateur membre",
        description: "Génère un token JWT pour les utilisateurs du front.",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "username", type: "string"),
                    new OA\Property(property: "password", type: "string")
                ],
                type: "object"
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Token généré",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "token", type: "string")
                    ],
                    type: "object"
                )
            ),
            new OA\Response(response: 401, description: "Invalid credentials")
        ]
    )]
    #[OA\Tag(name: 'authentification')]
    public function loginUser(Request $request): JsonResponse
    {
        return new JsonResponse(['message' => 'Cette route est gérée par LexikJWTAuthenticationBundle'], 200);
    }

    #[Route('/api/auth/send_mail', name: 'api_auth_send_mail', methods: ['POST',"GET"])]
    public function sendMail(Request $request,SendMailService $sendMailService): JsonResponse
    {
        $info_user = [
            'login' => "konatenhamed@gmail.com",
            'password' => "eeeee"
        ];

        $context = compact('info_user');

        // TO DO
        $sendMailService->send(
            'tester@myonmci.ci',
            "konatenhamed@gmail.com",
            'Informations',
            'content_mail',
            $context
        );
       
        return new JsonResponse(['message' => 'Cette route est gérée par LexikJWTAuthenticationBundle'], 200);
    }

    #[Route('/api/auth/login_check', name: 'api_auth_login_check', methods: ['POST'])]
    #[OA\Post(
        summary: "Authentification admin",
        description: "Génère un token JWT pour les administrateurs.",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "username", type: "string"),
                    new OA\Property(property: "password", type: "string")
                ],
                type: "object"
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Token généré",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "token", type: "string")
                    ],
                    type: "object"
                )
            ),
            new OA\Response(response: 401, description: "Invalid credentials")
        ]
    )]
    #[OA\Tag(name: 'authentification')]
    public function loginAdmin(Request $request): JsonResponse
    {
        return new JsonResponse(['message' => 'Cette route est gérée par LexikJWTAuthenticationBundle'], 200);
    }


}
