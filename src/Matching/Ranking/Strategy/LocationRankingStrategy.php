<?php

namespace App\Matching\Ranking\Strategy;

use App\Entity\User;

class LocationRankingStrategy implements RankingStrategyInterface
{

    public function rank(User $user, iterable $matchings): iterable
    {
        return $matchings;
    }
}
