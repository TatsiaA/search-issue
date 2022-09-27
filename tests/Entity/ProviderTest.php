<?php

namespace App\Tests\Entity;

use App\Entity\Provider;
use PHPUnit\Framework\TestCase;

class ProviderTest extends TestCase
{
    /**
     * @return void
     */
    public function testProvider(): void
    {
        $this->assertClassHasAttribute('name', Provider::class);
        $this->assertClassHasAttribute('base_url', Provider::class);
        $this->assertClassHasAttribute('words', Provider::class);
    }
}
