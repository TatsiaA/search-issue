<?php

namespace App\Service;

use App\Entity\Provider;
use App\Entity\Word;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class StoreService
{
    public function __construct(public readonly ManagerRegistry $doctrine)
    {
    }

    public function findWord(HttpClientInterface $client, string $term, string $provider): array
    {
        $repositoryProvide = $this->doctrine->getRepository(Provider::class);
        $queryToProvide = $repositoryProvide->createQueryBuilder('p')
            ->where('p.name = :provider')
            ->setParameter('provider', $provider)
            ->getQuery()
            ->getResult();

        $repository = $this->doctrine->getRepository(Word::class);
        $query = $repository->createQueryBuilder('w')
            ->andWhere('w.term = :term')
//            ->andWhere('w.provider = :term')
            ->setParameter('term', $term)
            ->getQuery()
            ->getResult();

        if ($query) {
            $score = $query[0]->getScore();
        } else {
            $url = $queryToProvide[0]->getBaseUrl();
            $contentSucks = $this->getContent($client, $term, $url);
            $contentRocks = $this->getContent($client, $term, $url, 'rocks');
            $score = round(
                $contentRocks['total_count'] / ($contentSucks['total_count'] + $contentRocks['total_count']) * 10,
                2
            );

            $query = new Word();
            $query->setTerm($term);
            $query->setScore($score);
            $repository->add($query, true);
        }
        return [
            'term' => $term,
            'score' => $score
        ];
    }

    public function getContent(
        HttpClientInterface $client,
        string $term,
        string $url,
        string $additionalParam = 'sucks'
    ): array {
        $response = $client->request(
            'GET',
            $url . '?q=' . $term . '+' . $additionalParam
        );

        $response->getContent();

        return $response->toArray();
    }
}