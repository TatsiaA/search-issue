<?php

namespace App\Service\Providers;

use App\Enum\Provider as ProviderEnum;

class GithubProviderService extends ProviderService
{
    public function getProviderName(): string
    {
        return ProviderEnum::GITHUB->value;
    }

    public function calculateNewScore(): float
    {
        $contentRocks = $this->getContent();
        $contentSucks = $this->getContent('sucks');

        return round(
            $contentRocks['total_count'] / ($contentSucks['total_count'] + $contentRocks['total_count']) * 10,
            2
        );
    }

    private function getContent(string $additionalParam = 'rocks'): array
    {
        $data = [
            'q' => $this->term . ' ' . $additionalParam,
        ];

        return $this->client->request(
            'GET',
            $this->baseUrl . '?' . http_build_query($data),
            [
                'headers' => [
                    'Accept' => 'application/vnd.github+json',
                ],
            ]
        )->toArray(false);
    }
}
