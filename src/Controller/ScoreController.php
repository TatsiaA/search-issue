<?php

namespace App\Controller;

use App\Service\StoreService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ScoreController extends AbstractController
{

    /**
     * @param HttpClientInterface $client
     */
    public function __construct(public readonly HttpClientInterface $client)
    {
    }

    /**
     * @param Request $request
     * @param StoreService $storeService
     * @return Response
     */
    #[Route('/score', name: 'app_score', methods: ['GET'])]
    public function score(Request $request,StoreService $storeService): Response
    {
        $term = $request->get('term');
        $provider = $request->get('provider');

        return $this->json($storeService->findWord($this->client, $term, $provider));
    }

}
