<?php

namespace App\Search\Factory;

use App\Search\Client\ConferenceApiClient;
use App\Search\ConferenceSearchInterface;
use App\Search\DatabaseConferenceSearch;
use Psr\Container\ContainerInterface;

class ConferenceSearchFactory
{
    public function create(string $searchType, ContainerInterface $searchLocator): ConferenceSearchInterface
    {
        return $searchLocator->get($searchType);
    }
}
