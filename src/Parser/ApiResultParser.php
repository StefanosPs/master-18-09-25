<?php

namespace App\Parser;

use App\Entity\Conference;
use App\Entity\Organization;
use App\Transformer\ApiToConferenceTransformer;
use App\Transformer\ApiToOrganizationTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ApiResultParser
{
    protected bool $isOrgOrAdmin = false;
    public function __construct(
        protected readonly EntityManagerInterface $manager,
        protected readonly ApiToConferenceTransformer $conferenceTransformer,
        protected readonly ApiToOrganizationTransformer $organizationTransformer,
        protected readonly AuthorizationCheckerInterface $checker
    ) {
        $this->isOrgOrAdmin = $this->checker->isGranted('ROLE_ORGANIZER') || $this->checker->isGranted('ROLE_WEBSITE');
    }

    public function parseResults(array $results): iterable
    {
        return \array_map(function (array $apiEvent) {
            $conference = $this->findOrCreateConference($apiEvent);

            foreach ($apiEvent['organizations'] as $org) {
                $entity = $this->findOrCreateOrganization($org);
                $entity->addConference($conference);
            }

            if ($this->isOrgOrAdmin) {
                $this->manager->flush();
            }

            return $conference;
        },$results);
    }

    private function findOrCreateConference(array $apiEvent): Conference
    {
        $conference = $this->manager
            ->getRepository(Conference::class)
            ->findOneBy([
                'name' => $apiEvent['name'],
                'startAt' => new \DateTimeImmutable($apiEvent['startDate'])
            ]);

        if (null === $conference) {
            $conference = $this->conferenceTransformer->transform($apiEvent);

            if ($this->isOrgOrAdmin) {
                $this->manager->persist($conference);
            }
        }

        return $conference;
    }

    private function findOrCreateOrganization($org): Organization
    {
        $entity = $this->manager->getRepository(Organization::class)->findOneBy(['name' => $org['name']]);

        if (null === $entity) {
            $entity = $this->organizationTransformer->transform($org);

            if ($this->isOrgOrAdmin) {
                $this->manager->persist($entity);
            }
        }

        return $entity;
    }
}
