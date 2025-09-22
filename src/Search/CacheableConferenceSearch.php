<?php

namespace App\Search;

use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

#[AsDecorator(ConferenceSearchInterface::class)]
class CacheableConferenceSearch implements ConferenceSearchInterface
{
    public function __construct(
        private readonly ConferenceSearchInterface $inner,
        private readonly TagAwareCacheInterface $cache,
    ) {}

    public function search(?string $name): array
    {
        return $this->cache->get(md5($name), function (ItemInterface $item) use ($name) {
            $item->expiresAfter(3600)
                ->tag('devevents-api');

            return $this->inner->search($name);
        });
    }
}
