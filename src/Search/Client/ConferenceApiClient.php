<?php

namespace App\Search\Client;

use App\Search\ConferenceSearchInterface;
use Symfony\Component\DependencyInjection\Attribute\Lazy;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Lazy]
class ConferenceApiClient implements ConferenceSearchInterface
{
    public function __construct(
        private readonly HttpClientInterface $conferencesClient,
    ) {}

    public function search(?string $name): array
    {
        return $this->conferencesClient->request(
            'GET',
            '/events',
            ['query' => ['name' => $name]]
        )->toArray();
    }
}
