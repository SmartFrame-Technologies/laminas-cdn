<?php

namespace Smartframe\Cdn\Service\Dictionary\Cloudflare;

use Smartframe\Cdn\Service\Dictionary\AdapterInterface;
use Smartframe\Cdn\Service\Dictionary\KeysCache;
use Smartframe\Cdn\Service\Dictionary\ItemValue;
use Cloudflare\API\Endpoints\KeyValue;

class CloudflareAdapter implements AdapterInterface
{
    private KeyValue $kvEndpoint;
    private KeysCache $keysCache;

    public function __construct(KeyValue $kvEndpoint, KeysCache $keysCache)
    {
        $this->kvEndpoint = $kvEndpoint;
        $this->keysCache = $keysCache;
    }


    public function listKeys(string $cacheId, string $dictionaryId, int $limit = null, string $cursor = null, string $prefix = null): \Generator
    {
        $object = $this->keysCache->get();
        if ($object === null) {
            $object = $this->kvEndpoint->listKeys($cacheId, $dictionaryId, $limit, $cursor, $prefix);
            $this->keysCache->store($object);
        }

        foreach($object->result as $keyObj) {
            try {
                yield $keyObj->name => $this->getKeyValue($cacheId, $dictionaryId, $keyObj->name);
            } catch(\Exception $exception) {
                yield $keyObj->name => null;
            }
        }
    }

    public function getKeyValue(string $cacheId, string $dictionaryId, string $key): ?ItemValue
    {
        return CloudflareLazyLoadingItemValue::createByValueCallback(fn() => $this->kvEndpoint->getKeyValue($cacheId, $dictionaryId, $key));
    }

    public function setKeyValue(string $cacheId, string $dictionaryId, string $key, ItemValue $value, array $options = []): bool
    {
        $keyValue = $value->getValue();

        try {
            //need to be done because Guzzle Client will encode value:
            $keyValue = json_decode($keyValue, true);
        } catch(\Exception $exception) {}

        $result = $this->kvEndpoint->setKeyValue($cacheId, $dictionaryId, $key, $keyValue, $options['metadata'] ?? [], $options['expiration'] ?? null, $options['expirationTtl'] ?? null);

        if ($result) {
            $this->keysCache->clear();
        }

        return $result;
    }

    public function setMultipleKeysValues(string $serviceId, string $dictionaryId, array $data, array $options = []): bool
    {
        foreach($data as $key => $value) {
            if ($value instanceof ItemValue) {
                $data[$key] = $value->getValue();
            }
        }

        $result = $this->kvEndpoint->setMultipleKeysValues($serviceId, $dictionaryId, $data, $options['metadata'] ?? [], $options['expiration'] ?? null, $options['expirationTtl'] ?? null);

        if ($result) {
            $this->keysCache->clear();
        }

        return $result;
    }

    public function deleteKey(string $cacheId, string $dictionaryId, string $key): bool
    {
        $result = $this->kvEndpoint->deleteKey($cacheId, $dictionaryId, $key);

        if ($result) {
            $this->keysCache->clear();
        }

        return $result;
    }

    public function deleteMultipleKeys(string $cacheId, string $dictionaryId, array $keys): bool
    {
        $result = $this->kvEndpoint->deleteMultipleKeys($cacheId, $dictionaryId, $keys);

        if ($result) {
            $this->keysCache->clear();
        }

        return $result;
    }
}
