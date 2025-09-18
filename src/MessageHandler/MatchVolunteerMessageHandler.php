<?php

namespace App\MessageHandler;

use App\Matching\Strategy\MatchingStrategyInterface;
use App\Message\MatchVolunteerMessage;
use App\Repository\UserRepository;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;
use Symfony\Component\DependencyInjection\Attribute\AutowireLocator;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use function Symfony\Component\DependencyInjection\Loader\Configurator\iterator;

#[AsMessageHandler]
final class MatchVolunteerMessageHandler
{
    public function __construct(
        /**
         * @var MatchingStrategyInterface[]
         */
        #[AutowireIterator('app.matching_strategy', defaultIndexMethod: 'getName')]
        private iterable $strategies, // ['tag'=> new TagBasedStrategy, ...]
        private readonly UserRepository $userRepository,
    )
    {
        $this->strategies = $strategies instanceof \Traversable ? iterator_to_array($strategies) : $strategies;
    }

    public function __invoke(MatchVolunteerMessage $message): void
    {
        $user = $this->userRepository->find($message->userId);
        if (null === $user) {
            throw new \InvalidArgumentException('User not found');
        }

        $strategy = $this->strategies[$message->strategyName];

        dump($strategy->match($user));
    }
}
