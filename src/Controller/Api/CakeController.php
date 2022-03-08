<?php

namespace App\Controller\Api;

use App\Repository\CakeRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CakeController
{
    private CakeRepository $repository;

    public function __construct(CakeRepository $repository)
    {
        $this->repository = $repository;
    }

    public function list(Request $request): JsonResponse
    {
        $contents = $this->repository->findAll();

        if (0 === \count($contents)) {
            return new JsonResponse(null, 204);
        }

        $cakes = array_map(function ($cake) {
            return [
                'id' => $cake->getId(),
                'name' => $cake->getName(),
                'description' => $cake->getDescription(),
                'price' => $cake->getPrice(),
                'image' => $cake->getImage()
            ];
        }, $contents);

        return new JsonResponse($cakes, 200);
    }
}
