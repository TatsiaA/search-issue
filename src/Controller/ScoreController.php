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

    /**
     * @param HttpClientInterface $client
     */
    public function __construct(public readonly HttpClientInterface $client)
    {
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
        $provider = $request->get('provider');
        $repositoryProvide = $doctrine->getRepository(Provider::class);
        $repositoryProvide->createQueryBuilder('p')
            ->where('p.name = :provider')
            ->setParameter('provider', $provider)
            ->getQuery()
            ->getResult();

        $repository = $doctrine->getRepository(Word::class);
        $query = $repository->createQueryBuilder('w')
            ->where('w.term = :term')
            ->where('w.term = :term')
            ->setParameter('term', $term)
            ->getQuery()
            ->getResult();

        if (!$query) {
            $contentSucks = $this->getContent($repositoryProvide->url, $term);
            $contentRocks = $this->getContent($repositoryProvide->url, $term, 'rocks');
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
     * @param string $url
     * @param string $term
     * @param string $additionalParam
     * @return array
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getContent(string $url, string $term, string $additionalParam = 'sucks'): array
    {
        $response = $this->client->request(
            'GET',
            $url . '?q=' . $term . '+' . $additionalParam .  '&sort=created&order=asc'
        );

        $response->getContent();

        return $response->toArray();
    }

}
