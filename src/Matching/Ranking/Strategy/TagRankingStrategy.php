<?php

namespace App\Matching\Ranking\Strategy;

use App\Entity\Conference;
use App\Entity\User;
use Tiriel\MatchingBundle\Interface\MatchableUserInterface;
use Tiriel\MatchingBundle\Matching\Ranking\Strategy\AbstractRankingStrategy;

class TagRankingStrategy extends AbstractRankingStrategy
{
    public function getMatchableFromUser(MatchableUserInterface $user): array
    {
        /** @var User $user */
        return $user->getVolunteerProfile()->getInterests()->toArray();
    }

    public function getMatchablesFromEntity(object $entity): iterable
    {
        /** @var Conference $entity */
        return $entity->getTags()->toArray();
    }
}
