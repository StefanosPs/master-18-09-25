<?php

namespace App\MessageHandler;

use App\Matching\Handler\MatcherInterface;
use App\Message\MatchVolunteerMessage;
use App\Repository\UserRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class MatchVolunteerMessageHandler
{
    public function __construct(
        private readonly MatcherInterface $handler,
        private readonly UserRepository $userRepository,
    ) {}

    public function __invoke(MatchVolunteerMessage $message): void
    {
        $user = $this->userRepository->find($message->userId);
        if (null === $user) {
            throw new \InvalidArgumentException('User not found');
        }

        $strategy = $this->handler->match($user, $message->strategyName);

        dump($strategy);
    }
}
