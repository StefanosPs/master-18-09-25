<?php

namespace App\Matching\Handler;

use App\Entity\User;

interface MatcherInterface
{
    public function match(User $user, string $strategyName): iterable;
}
