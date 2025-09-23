<?php

namespace App\Search;

use App\Entity\Conference;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.search.conference')]
interface ConferenceSearchInterface
{
    public function search(?string $name): array;
}
