<?php

namespace App\Matching\Ranking\Strategy;

use App\Entity\Conference;
use App\Entity\User;

class SkillRankingStrategy implements RankingStrategyInterface
{

    public function rank(User $user, iterable $matchings): iterable
    {
        $userSkills = $user->getVolunteerProfile()?->getSkills() ?? [];
        $userSkillNames = [];
        
        // Extract skill names from user skills
        foreach ($userSkills as $skill) {
            $userSkillNames[] = $skill->getName();
        }
        
        // Convert iterable to array for sorting
        $conferences = iterator_to_array($matchings);
        
        // Sort conferences by number of matching skills (descending)
        usort($conferences, function (Conference $a, Conference $b) use ($userSkillNames) {
            $aMatches = $this->countMatchingSkills($a, $userSkillNames);
            $bMatches = $this->countMatchingSkills($b, $userSkillNames);
            
            return $bMatches <=> $aMatches; // Descending order
        });
        
        return $conferences;
    }
    
    private function countMatchingSkills(Conference $conference, array $userSkillNames): int
    {
        $matches = 0;
        
        foreach ($conference->getNeededSkills() as $neededSkill) {
            if (in_array($neededSkill->getName(), $userSkillNames, true)) {
                $matches++;
            }
        }
        
        return $matches;
    }
}
