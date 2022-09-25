<?php

namespace App\Service\Providers;

use App\Entity\Provider as ProviderEntity;

interface ProviderContract
{
    public function getProviderName(): string;

    public function getScore(): ?float;

    public function calculateNewScore(): float;

    public function getProviderEntity(): ProviderEntity;

    public function getTerm(): string;
}
