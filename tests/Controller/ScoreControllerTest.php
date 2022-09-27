<?php

namespace App\Tests\Controller;

use App\Service\ScoreService;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ScoreControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private UrlGeneratorInterface $router;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->router = static::getContainer()->get(UrlGeneratorInterface::class);
    }

    public function testErrorResponse(): void
    {
        $this->client->jsonRequest('GET', $this->router->generate('app_score', [
            'term' => 'php',
            'provider' => 'unknown',
        ]));

        $this->assertResponseStatusCodeSame(404);
    }

    public function testSuccessfulResponse(): void
    {
        $term = 'php';
        $score = 3.5;
        $container = static::getContainer();
        $scoreService = $this->getMockBuilder(ScoreService::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['rating'])
            ->getMock();
        $scoreService->method('rating')->willReturn([
            'term' => $term,
            'score' => $score,
        ]);
        $container->set(ScoreService::class, $scoreService);

        $this->client->jsonRequest('GET', $this->router->generate('app_score', [
            'term' => $term,
            'provider' => 'github',
        ]));

        $this->assertResponseIsSuccessful();

        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals($term, $response['term']);
        $this->assertEquals($score, $response['score']);
    }
}
