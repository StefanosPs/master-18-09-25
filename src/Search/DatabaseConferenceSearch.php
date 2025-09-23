<?php

namespace App\Search;

use App\Entity\Organization;
use App\Repository\ConferenceRepository;

class DatabaseConferenceSearch implements ConferenceSearchInterface
{
    private ?Organization $organization = null;

    private ?int $limit = null;

    public function __construct(
        private readonly ConferenceRepository $repository,
    ) {}

    public function setOrganization(?Organization $organization): DatabaseConferenceSearch
    {
        $this->organization = $organization;

        return $this;
    }

    public function setLimit(?int $limit): DatabaseConferenceSearch
    {
        $this->limit = $limit;

        return $this;
    }

    public function search(?string $name): array
    {
        if (null === $name) {
            return $this->repository->findAll();
        }

        return $this->repository->findLikeName($name);
    }

    public function fetchForOrganization(): iterable
    {
        if ($this->organization === null || $this->limit === null) {
            throw new \LogicException('Missing configuration.');
        }

        return $this->repository->fetchForOrganization($this->organization, $this->limit);
    }
}
