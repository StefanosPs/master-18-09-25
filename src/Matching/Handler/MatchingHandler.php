<?php

namespace App\Matching\Handler;

use App\Entity\User;
use App\Matching\Strategy\MatchingStrategyInterface;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

#[AsAlias]
class MatchingHandler implements MatcherInterface
{
    public function __construct(
        /**
         * @var MatchingStrategyInterface[]
         */
        #[AutowireIterator('app.matching_strategy', defaultIndexMethod: 'getName')]
        private iterable $strategies, // ['tag'=> new TagBasedStrategy, ...]
    )
    {
        $this->strategies = $strategies instanceof \Traversable ? iterator_to_array($strategies) : $strategies;
    }

    public function match(User $user, string $strategyName): iterable
    {
        return $this->strategies[$strategyName]->match($user);
    }
}
