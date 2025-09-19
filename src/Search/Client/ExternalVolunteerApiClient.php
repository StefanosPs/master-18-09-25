<?php

namespace App\Search\Client;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ExternalVolunteerApiClient
{
    public function __construct(
        private readonly HttpClientInterface $volunteersClient,
    ) {
    }

    public function fetchVolunteers(): array
    {
        return $this->volunteersClient->request(
            Request::METHOD_GET,
            '/tiriel/volunteer-api/volunteers'
        )->toArray();
    }
}
