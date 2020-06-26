<?php

namespace App\Controller\ApiV1;

use App\Service\SecurityService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/v1")
 */
class ApiController
{
    /**
     * @Route("/", name="api_v1_home", methods={"GET"})
     * @param SecurityService $securityService
     *
     * @return JsonResponse
     */
    public function index(SecurityService $securityService): JsonResponse
    {
        $user = $securityService->getConnectedUser();

        return new JsonResponse(['Hello ' . $user->getEmail()]);
    }
}
