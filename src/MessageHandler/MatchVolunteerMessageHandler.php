<?php

namespace App\MessageHandler;

use App\Message\MatchVolunteerMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class MatchVolunteerMessageHandler
{
    public function __invoke(MatchVolunteerMessage $message): void
    {
        dump($message->name);
    }
}
