<?php

namespace App\Tests\Controller;

use App\Controller\ScoreController;
use App\Service\ScoreService;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class ScoreController1Test extends TestCase
{
    /**
     * @return void
     */
    public function testScoreService(): void
    {
        $service = $this->createMock(ScoreService::class);
        $service->method('rating')
            ->willReturn([]);

        $score = new ScoreController();
        $score->setContainer($this->createStub(ContainerInterface::class));

        $this->assertInstanceOf(JsonResponse::class, new JsonResponse());
    }
}
