<?php

namespace App\Matching\Strategy;

use Tiriel\MatchingBundle\Interface\MatchableUserInterface;
use Tiriel\MatchingBundle\Matching\Strategy\AbstractMatchingStrategy;

class LocationMatchingStrategy extends AbstractMatchingStrategy
{

    protected function getMatchablesFromUser(MatchableUserInterface $user)
    {
        return [];
    }

    protected function getMatchableName(): string
    {
        return '';
    }

    protected function getBaseEntityName(): string
    {
        return '';
    }

    public static function getName(): string
    {
        return 'location';
    }
}
