<?php

declare(strict_types=1);

namespace Smartframe\Cdn\Service\Purge;

use Smartframe\Cdn\Exception\WildcardUrlNotSupportedException;

interface PurgeInterface
{
    /** @throws WildcardUrlNotSupportedException */
    public function url(string $cacheId, string $url): bool;

    public function key(string $cacheId, string $keyId): bool;

    public function all(string $cacheId): bool;

    public function isWildcardUrlSupported(): bool;
}
