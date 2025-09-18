<?php

namespace App\Parser;

use App\Entity\Conference;
use App\Entity\Organization;
use App\Transformer\ApiToConferenceTransformer;
use App\Transformer\ApiToOrganizationTransformer;
use Doctrine\ORM\EntityManagerInterface;

class ConferenceApiParser
{
    public function __construct(
        private readonly EntityManagerInterface $manager,
        private readonly ApiToConferenceTransformer $conferenceTransformer,
        private readonly ApiToOrganizationTransformer $organizationTransformer,
    ) {}

    public function parseApiResults(array $results): array
    {
        return \array_map(function (array $apiConf) {
            $conference = $this->findOrCreateConference($apiConf);

            foreach ($apiConf['organizations'] as $apiOrg) {
                $organization = $this->findOrCreateOrganization($apiOrg);

                $organization->addConference($conference);
                $conference->addOrganization($organization);
            }

            $this->manager->flush();

            return $conference;
        }, $results);
    }

    private function findOrCreateConference(array $apiConf): Conference
    {
        $conference = $this->manager->getRepository(Conference::class)->findOneBy([
            'name' => $apiConf['name'],
            'startAt' => new \DateTimeImmutable($apiConf['startDate']),
        ]);

        if (null === $conference) {
            $conference = $this->conferenceTransformer->transform($apiConf);
            $this->manager->persist($conference);
        }

        return $conference;
    }

    private function findOrCreateOrganization(array $apiOrg): Organization
    {
        $organization = $this->manager->getRepository(Organization::class)->findOneBy(['name' => $apiOrg['name']]);

        if (null === $organization) {
            $organization = $this->organizationTransformer->transform($apiOrg);
            $this->manager->persist($organization);
        }

        return $organization;
    }
}
