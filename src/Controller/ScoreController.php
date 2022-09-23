<?php

namespace App\Controller;

use App\Service\ScoreService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ScoreController extends AbstractController
{
    /**
     * @param Request $request
     * @param ScoreService $scoreService
     * @return Response
     */
    #[Route('/score', name: 'app_score', methods: ['GET'])]
    public function score(Request $request, ScoreService $scoreService): Response
    {
        $term = $request->get('term');
        $provider = $request->get('provider');

        return $this->json($scoreService->rating($term, $provider));
    }
}
