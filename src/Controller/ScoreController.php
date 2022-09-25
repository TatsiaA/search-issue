<?php

namespace App\Controller;

use App\Service\ScoreService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ScoreController extends AbstractController
{
    /**
     * @param ScoreService $scoreService
     * @return JsonResponse
     */
    #[Route('/score', name: 'app_score', methods: ['GET'])]
    public function score(ScoreService $scoreService): JsonResponse
    {
        return $this->json($scoreService->rating());

    }
}
