<?php

namespace App\Entity;

use App\Repository\ProviderRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProviderRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Provider
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private string $name;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private string $base_url;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\ManyToMany(targetEntity: Word::class, inversedBy: 'providers')]
    private Collection $word;

    public function __construct()
    {
        $this->word = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getBaseUrl(): string
    {
        return $this->base_url;
    }

    public function setBaseUrl(string $base_url): self
    {
        $this->base_url = $base_url;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection<int, Word>
     */
    public function getWord(): Collection
    {
        return $this->word;
    }

    public function addWord(Word $word): self
    {
        if (!$this->word->contains($word)) {
            $this->word->add($word);
        }

        return $this;
    }

    public function removeWord(Word $word): self
    {
        $this->word->removeElement($word);

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
    }
}
