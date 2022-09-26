<?php

namespace App\Tests\Controller;

use App\Exception\AppraiserException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ScoreController2Test extends WebTestCase
{
    /**
     * @test
     * @return void
     */
    public function testScoreControllerException(): void
    {
        $client = static::createClient();

        $client->request('GET', '/score?term=java&provider=some_provider');
        $this->assertResponseStatusCodeSame(404);
    }

    /**
     * @test
     * @return void
     */
    public function testScoreController(): void
    {
        $client = static::createClient();
        $client->request('GET', '/score?term=java&provider=github');
        $this->assertResponseIsSuccessful();
    }
}
