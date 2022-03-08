<?php

declare(strict_types=1);

namespace Smartframe\Cdn\Service\Purge\Cloudflare;

use Cloudflare\API\Endpoints\EndpointException;
use Cloudflare\API\Endpoints\Zones;
use Smartframe\Cdn\Exception\WildcardUrlNotSupportedException;
use Smartframe\Cdn\Service\Purge\PurgeInterface;

class CloudflarePurge implements PurgeInterface
{
    public const CACHE_KEY_HEADER_KEY = 'Cache-Tag';

    private Zones $zonesEndpoint;

    public function __construct(
        Zones $zonesEndpoint
    ) {
        $this->zonesEndpoint = $zonesEndpoint;
    }

    /**
     * @throws EndpointException
     * @throws WildcardUrlNotSupportedException
     */
    public function url(string $cacheId, string $url): bool
    {
        if (false !== \strpos($url, '*')) {
            throw new WildcardUrlNotSupportedException();
        }

        return $this->zonesEndpoint->cachePurge($cacheId, [$url]);
    }

    /** @throws EndpointException */
    public function key(string $cacheId, string $keyId): bool
    {
        return $this->zonesEndpoint->cachePurge($cacheId, [], [$keyId]);
    }

    /** @throws EndpointException */
    public function hostname(string $cacheId, string $hostname): bool
    {
        return $this->zonesEndpoint->cachePurge($cacheId, [], [], [$hostname]);
    }

    public function all(string $cacheId): bool
    {
        return $this->zonesEndpoint->cachePurgeEverything($cacheId);
    }

    /**
     * @codeCoverageIgnore
     */
    public function isWildcardUrlSupported(): bool
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getCacheKeyHeaderKey(): string
    {
        return self::CACHE_KEY_HEADER_KEY;
    }
}