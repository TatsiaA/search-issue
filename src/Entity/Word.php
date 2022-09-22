<?php

namespace App\Entity;

use App\Repository\WordRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WordRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Word
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $term = null;

    #[ORM\Column]
    private ?float $score = null;

    #[ORM\Column]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTerm(): ?string
    {
        return $this->term;
    }

    public function setTerm(string $term): self
    {
        $this->term = $term;

        return $this;
    }

    public function getScore(): ?float
    {
        return $this->score;
    }

    public function setScore(float $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->setUpdatedAt(new DateTimeImmutable('now'));
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new DateTimeImmutable('now'));
        }
    }
    #[ORM\PreUpdate]
    public function preUpdate(): void
    {
        $this->setUpdatedAt(new DateTimeImmutable('now'));
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new DateTimeImmutable('now'));
        }
    }
}
