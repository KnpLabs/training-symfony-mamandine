<?php

namespace App\Controller\Api;

use App\Security\UserTokenGenerator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TokenController
{
    private UserTokenGenerator $userTokenGenerator;

    public function __construct(UserTokenGenerator $userTokenGenerator)
    {
        $this->userTokenGenerator = $userTokenGenerator;
    }

    public function create(Request $request): JsonResponse
    {
        $token = $this->userTokenGenerator->create($request);

        return new JsonResponse([
            'token' => $token
        ], 201);
    }

    public function refresh(Request $request): JsonResponse
    {
        $token = $this->userTokenGenerator->refresh($request);

        return new JsonResponse([
            'token' => $token
        ], 201);
    }
}
