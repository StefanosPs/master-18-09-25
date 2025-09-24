<?php

namespace App\Matching\Strategy;

use App\Entity\User;
use App\Repository\ConferenceRepository;
use Tiriel\MatchingBundle\Interface\MatchableUserInterface;
use Tiriel\MatchingBundle\Matching\Strategy\AbstractMatchingStrategy;

class TagMatchingStrategy extends AbstractMatchingStrategy
{
    public function __construct(
        ConferenceRepository $repository,
    ) {
        $this->repository = $repository;
    }

    public static function getName(): string
    {
        return 'tag';
    }

    protected function getMatchablesFromUser(MatchableUserInterface $user)
    {
        /** @var User $user */
        return $user->getVolunteerProfile()->getInterests()->toArray();
    }

    protected function getMatchableName(): string
    {
        return 'tags';
    }

    protected function getBaseEntityName(): string
    {
        return 'conference';
    }
}
