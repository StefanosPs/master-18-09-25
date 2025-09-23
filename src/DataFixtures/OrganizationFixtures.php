<?php

namespace App\DataFixtures;

use App\Entity\Conference;
use App\Entity\Organization;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class OrganizationFixtures extends Fixture implements DependentFixtureInterface
{
    public const SF_ORG = 'sf_org';

    public function load(ObjectManager $manager): void
    {
        $org = (new Organization())
            ->setName('Symfony')
            ->setCreatedAt(new \DateTimeImmutable('2018'))
            ->setPresentation('Symfony SAS is the company behind Symfony, the PHP Open-Source framework.')
        ;
        for ($i = 1; $i <= 10; $i++) {
            $org->addConference($this->getReference(ConferenceFixtures::SF_LIVE.$i, Conference::class));
        }

        $manager->persist($org);
        $manager->flush();
        $this->addReference(self::SF_ORG, $org);
    }

    public function getDependencies(): array
    {
        return [
            ConferenceFixtures::class,
        ];
    }
}
