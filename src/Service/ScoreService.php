<?php

namespace App\Service;

use App\Entity\Word;
use App\Exception\AppraiserException;
use App\Repository\WordRepository;
use App\Service\Providers\ProviderContract;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ScoreService
{
    public function __construct(
        private readonly ProviderContract $provider,
        private readonly ManagerRegistry $doctrine,
        private readonly ValidatorInterface $validator,
        private readonly LoggerInterface $logger,
    ) {
    }

    /**
     * Finds or calculates term score rating
     *
     * @return array
     */
    public function rating(): array
    {
        $score = $this->provider->getScore();

        if ($score === null) {
            $score = $this->provider->calculateNewScore();
            $this->storeNewScore($score);
        }

        return [
            'term' => $this->provider->getTerm(),
            'score' => $score,
        ];
    }

    private function storeNewScore(float $score): void
    {
        $word = new Word();
        $word->setScore($score);
        $word->setTerm($this->provider->getTerm());
        $word->setProvider($this->provider->getProviderEntity());
        $this->validate($word);
        /** @var WordRepository $wordRepository */
        $wordRepository = $this->doctrine->getRepository(Word::class);
        $wordRepository->add($word, true);
    }

    private function validate(Word $word): void
    {
        $errors = $this->validator->validate($word);
        if (count($errors) > 0) {
            /** @phpstan-ignore-next-line */
            $this->logger->critical((string)$errors);
            throw new AppraiserException();
        }
    }
}
