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
    public const CACHE_KEY_SEPARATOR = ',';

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
    public function url(string $cacheId, string|array $urls): bool
    {
        if(is_string($urls)) {
            $urls = [$urls];
        }

        foreach($urls as $url) {
            if (false !== \strpos($url, '*')) {
                throw new WildcardUrlNotSupportedException();
            }
        }

        return $this->zonesEndpoint->cachePurge($cacheId, $urls);
    }

    /** @throws EndpointException */
    public function key(string $cacheId, string|array $keysId): bool
    {
        if(is_string($keysId)) {
            $keysId = [$keysId];
        }
        return $this->zonesEndpoint->cachePurge($cacheId, [], $keysId);
    }

    /** @throws EndpointException */
    public function hostname(string $cacheId, string|array $hostnames): bool
    {
        if(is_string($hostnames)) {
            $hostnames = [$hostnames];
        }

        return $this->zonesEndpoint->cachePurge($cacheId, [], [], $hostnames);
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

    /**
     * @codeCoverageIgnore
     */
    public function getCacheKeySeparator(): string
    {
        return self::CACHE_KEY_SEPARATOR;
    }
}
