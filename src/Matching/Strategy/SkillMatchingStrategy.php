<?php

namespace App\Matching\Strategy;

use App\Entity\User;
use App\Repository\ConferenceRepository;
use Tiriel\MatchingBundle\Interface\MatchableUserInterface;
use Tiriel\MatchingBundle\Matching\Strategy\AbstractMatchingStrategy;

class SkillMatchingStrategy extends AbstractMatchingStrategy
{
    public function __construct(
        ConferenceRepository $repository,
    ) {
        $this->repository = $repository;
    }

    public static function getName(): string
    {
        return 'skill';
    }

    protected function getMatchablesFromUser(MatchableUserInterface $user)
    {
        /** @var User $user */
        return $user->getVolunteerProfile()->getSkills()->toArray();
    }

    protected function getMatchableName(): string
    {
        return 'skills';
    }

    protected function getBaseEntityName(): string
    {
        return 'conference';
    }
}
