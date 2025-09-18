<?php

namespace App\Matching\Strategy;

use App\Entity\User;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.matching_strategy')]
interface MatchingStrategyInterface
{
    public function match(User $user): iterable;

    public static function getName(): string;
}
