<?php

namespace App\MessageHandler;

use App\Message\MatchVolunteerMessage;
use App\Repository\UserRepository;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireLocator;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class MatchVolunteerMessageHandler
{
    public function __construct(
        #[AutowireLocator('app.matching_strategy', defaultIndexMethod: 'getName')]
        private readonly ContainerInterface $strategies, // ['tag'=> new TagBasedStrategy, ...]
        private readonly UserRepository $userRepository,
    )
    {
    }

    public function __invoke(MatchVolunteerMessage $message): void
    {
        $user = $this->userRepository->find($message->userId);
        if (null === $user) {
            throw new \InvalidArgumentException('User not found');
        }

        $strategy = $this->strategies->get($message->strategyName);

        dump($strategy->match($user));
    }
}
