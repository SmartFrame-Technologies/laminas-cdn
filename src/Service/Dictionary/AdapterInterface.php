<?php

namespace Smartframe\Cdn\Service\Dictionary;

interface AdapterInterface
{
    public function listKeys(string $cacheId, string $dictionaryId, int $limit = null, string $cursor = null, string $prefix = null): \Generator;
    public function getKeyValue(string $cacheId, string $dictionaryId, string $key): ?ItemValue;
    public function setKeyValue(string $cacheId, string $dictionaryId, string $key, ItemValue $value, array $options = []): bool;
    public function setMultipleKeysValues(string $serviceId, string $dictionaryId, array $data, array $options = []): bool;
    public function deleteKey(string $cacheId, string $dictionaryId, string $key): bool;
    public function deleteMultipleKeys(string $cacheId, string $dictionaryId, array $keys): bool;
}
