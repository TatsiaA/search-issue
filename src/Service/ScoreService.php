<?php

namespace App\Service;

use App\Entity\Provider;
use App\Entity\Word;
use App\Repository\WordRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ScoreService
{
    public function __construct(
        private readonly ManagerRegistry $doctrine,
        private readonly HttpClientInterface $client,
    ) {
    }

    public function rating(string $term, string $provider): array
    {
        /** @var WordRepository $wordRepository */
        $wordRepository = $this->doctrine->getRepository(Word::class);
        /** @var Word|null $word */
        $word = $wordRepository->findOneBy([
            'term' => $term,
        ]);
        $score = $word?->getScore();

        if ($score === null) {
            $score = $this->calculateNewScore($term, $provider);
            $word = new Word();
            $word->setTerm($term);
            $word->setScore($score);
            $wordRepository->add($word, true);
        }

        return [
            'term' => $term,
            'score' => $score
        ];
    }

    private function calculateNewScore(string $term, string $providerName): float
    {
        $providerRepository = $this->doctrine->getRepository(Provider::class);
        $provider = $providerRepository->findOneBy([
            'name' => $providerName,
        ]);
        $url = $provider->getBaseUrl();
        $contentSucks = $this->getContent($term, $url, 'sucks');
        $contentRocks = $this->getContent($term, $url);

        return round(
            $contentRocks['total_count'] / ($contentSucks['total_count'] + $contentRocks['total_count']) * 10,
            2
        );
    }

    private function getContent(string $term, string $url, string $additionalParam = 'rocks'): array
    {
        $data = [
            'q' => $term . ' ' . $additionalParam,
        ];

        return $this->client->request('GET', $url . '?' . http_build_query($data))->toArray();
    }
}
