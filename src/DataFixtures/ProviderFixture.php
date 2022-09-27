<?php

namespace App\DataFixtures;

use App\Entity\Provider;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProviderFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
         $provider = new Provider();
         $provider->setName('github');
         $provider->setBaseUrl('https://api.github.com/search/issues');
         $manager->persist($provider);

        $manager->flush();
    }
}
