<?php

namespace App\Matching\Ranking\Strategy;

use App\Entity\Conference;
use App\Entity\User;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.ranking_strategy')]
interface RankingStrategyInterface
{
    /**
     * @param User $user
     * @param Conference[] $matchings
     * @return iterable
     */
    public function rank(User $user, iterable $matchings): iterable;
}
