<?php

namespace App\Controller;

use App\Service\DefaultService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController
{
    /**
     * @Route("/", name="home", methods={"GET"})
     * @param DefaultService $defaultservice
     * @return JsonResponse
     */
    public function index(
        DefaultService $defaultservice
    ): JsonResponse {
        return new JsonResponse([
            $defaultservice->getHomeText()
        ]);
    }
}
