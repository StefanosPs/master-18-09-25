<?php

namespace App\Matching\Ranking\Strategy;

use App\Entity\Conference;
use App\Entity\User;

class TagRankingStrategy implements RankingStrategyInterface
{

    public function rank(User $user, iterable $matchings): iterable
    {
        $userInterests = $user->getVolunteerProfile()?->getInterests() ?? [];
        $userInterestNames = [];
        
        // Extract tag names from user interests
        foreach ($userInterests as $interest) {
            $userInterestNames[] = $interest->getName();
        }
        
        // Convert iterable to array for sorting
        $conferences = iterator_to_array($matchings);
        
        // Sort conferences by number of matching tags (descending)
        usort($conferences, function (Conference $a, Conference $b) use ($userInterestNames) {
            $aMatches = $this->countMatchingTags($a, $userInterestNames);
            $bMatches = $this->countMatchingTags($b, $userInterestNames);
            
            return $bMatches <=> $aMatches; // Descending order
        });
        
        return $conferences;
    }
    
    private function countMatchingTags(Conference $conference, array $userInterestNames): int
    {
        $matches = 0;
        
        foreach ($conference->getTags() as $tag) {
            if (in_array($tag->getName(), $userInterestNames, true)) {
                $matches++;
            }
        }
        
        return $matches;
    }
}
