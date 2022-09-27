<?php

namespace App\Tests\Entity;

use App\Entity\Word;
use App\Service\ScoreService;
use PHPUnit\Framework\TestCase;

class WordTest extends TestCase
{
    /**
     * @return void
     */
    public function testWord(): void
    {
        $this->assertClassHasAttribute('term', Word::class);
        $this->assertClassHasAttribute('score', Word::class);
        $this->assertClassHasAttribute('provider', Word::class);

    }
}
