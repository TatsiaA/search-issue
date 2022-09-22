<?php

namespace App\Controller;

use App\Entity\Word;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ScoreController extends AbstractController
{
    public HttpClientInterface $client;

    /**
     * @param HttpClientInterface $client
     */
    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    #[Route('/score', name: 'app_score', methods: ['GET'])]
    public function score(Request $request, ManagerRegistry $doctrine): Response
    {
        $term = $request->get('term');

        $repository = $doctrine->getRepository(Word::class);
        $query = $repository->createQueryBuilder('w')
            ->where('w.term = :term')
            ->setParameter('term', $term)
            ->getQuery()
            ->getResult();

        if (!$query) {
            $contentSucks = $this->getContent($term);
            $contentRocks = $this->getContent($term, 'rocks');
            $score = round(
                $contentRocks['total_count'] / ($contentSucks['total_count'] + $contentRocks['total_count']) * 10,
                2
            );

            $query = new Word();
            $query->setTerm($term);
            $query->setScore($score);

            $repository->add($query, true);
        } else {
            $score = $query[0]->getScore();
        }
        $output = [
            'term' => $term,
            'score' => $score
        ];
        return $this->json($output);
    }

    /**
     * @param string $term
     * @param string $additionalParam
     * @return array
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getContent(string $term, string $additionalParam = 'sucks'): array
    {
        $response = $this->client->request(
            'GET',
            'https://api.github.com/search/issues?q=' . $term . '+' . $additionalParam .  '&sort=created&order=asc'
        );

        $response->getContent();

        return $response->toArray();
    }
}
