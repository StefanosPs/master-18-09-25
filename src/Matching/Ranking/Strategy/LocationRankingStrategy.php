<?php

namespace App\Matching\Ranking\Strategy;

use Tiriel\MatchingBundle\Interface\MatchableUserInterface;
use Tiriel\MatchingBundle\Matching\Ranking\Strategy\AbstractRankingStrategy;

class LocationRankingStrategy extends AbstractRankingStrategy
{
    public function getMatchableFromUser(MatchableUserInterface $user): array
    {
        return [];
    }

    public function getMatchablesFromEntity(object $entity): iterable
    {
        return [];
    }
}
