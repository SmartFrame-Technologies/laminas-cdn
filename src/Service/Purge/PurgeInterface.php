<?php

declare(strict_types=1);

namespace Smartframe\Cdn\Service\Purge;

use Smartframe\Cdn\Exception\PurgeByHostnameNotSupportedException;
use Smartframe\Cdn\Exception\PurgeByKeyNotSupportedException;
use Smartframe\Cdn\Exception\WildcardUrlNotSupportedException;

interface PurgeInterface
{
    /** @throws WildcardUrlNotSupportedException */
    public function url(string $cacheId, string $url): bool;

    /** @throws PurgeByKeyNotSupportedException */
    public function key(string $cacheId, string $keyId): bool;

    /** @throws PurgeByHostnameNotSupportedException */
    public function hostname(string $cacheId, string $hostname): bool;

    public function all(string $cacheId): bool;

    public function isWildcardUrlSupported(): bool;

    public function getCacheKeyHeaderKey(): string;
}
