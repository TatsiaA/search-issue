<?php

namespace App\Service\Providers;

use App\Entity\Provider as ProviderEntity;
use App\Entity\Word;
use App\Repository\WordRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class ProviderService implements ProviderContract
{
    /** @var ProviderEntity Provider Entity filtered by provider name  */
    protected readonly ProviderEntity $providerEntity;

    protected readonly string $baseUrl;

    protected string $term = '';

    abstract public function getProviderName(): string;

    abstract public function calculateNewScore(): float;

    /**
     * @param HttpClientInterface $client
     * @param ManagerRegistry $doctrine
     */
    public function __construct(
        protected readonly HttpClientInterface $client,
        protected readonly ManagerRegistry $doctrine,
    ) {
        $providerRepository = $this->doctrine->getRepository(ProviderEntity::class);
        $this->providerEntity = $providerRepository->findOneBy([
            'name' => $this->getProviderName(),
        ]);
        $this->baseUrl = $this->providerEntity->getBaseUrl();
    }

    public function setTerm(string $term): static
    {
        $this->term = $term;

        return $this;
    }

    /**
     * Get Provider Entity filtered by provider name
     *
     * @return ProviderEntity
     */
    public function getProviderEntity(): ProviderEntity
    {
        return $this->providerEntity;
    }

    public function getScore(): ?float
    {
        /** @var WordRepository $wordRepository */
        $wordRepository = $this->doctrine->getRepository(Word::class);
        /** @var Word|null $word */
        $word = $wordRepository->findOneBy([
            'term' => $this->term,
            'provider' => $this->providerEntity->getId(),
        ]);

        return $word?->getScore();
    }

    public function getTerm(): string
    {
        return $this->term;
    }
}
