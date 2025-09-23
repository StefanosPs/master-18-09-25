<?php

namespace App\Search\Configurator;

use App\Entity\Organization;
use App\Entity\User;
use App\Search\DatabaseConferenceSearch;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class DatabaseConferenceSearchConfigurator
{
    public function __construct(
        private readonly Security $security,
        #[Autowire(env: 'DB_SEARCH_LIMIT')]
        private readonly int $limit,
    ) {}

    public function configure(DatabaseConferenceSearch $search): void
    {
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw new \InvalidArgumentException('User must be an instance of User.');
        }

        $organization = $user->getOrganizations()->first();
        if (!$organization instanceof Organization) {
            throw new \InvalidArgumentException('The user must have at least one organization');
        }

        $search
            ->setOrganization($organization)
            ->setLimit($this->limit)
        ;
    }
}
