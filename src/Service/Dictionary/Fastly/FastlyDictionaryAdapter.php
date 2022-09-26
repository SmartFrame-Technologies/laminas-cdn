<?php

namespace Smartframe\Cdn\Service\Dictionary\Fastly;

use Smartframe\Cdn\Service\Dictionary\AdapterInterface;
use Smartframe\Cdn\Service\Dictionary\Exception\BadRequestException;
use Smartframe\Cdn\Service\Dictionary\Exception\BadResponseException;
use Smartframe\Cdn\Service\Dictionary\ItemValue;
use Smartframe\Cdn\Service\Dictionary\stdClass;
use Fastly\Api\DictionaryItemApi;
use Fastly\ApiException;

class FastlyDictionaryAdapter implements AdapterInterface
{
    public const CREATE_OPERATION = 'create';
    public const UPDATE_OPERATION = 'update';
    public const DELETE_OPERATION = 'delete';

    private DictionaryItemApi $fastlyClient;

    public function __construct(DictionaryItemApi $fastlyClient)
    {
        $this->fastlyClient = $fastlyClient;
    }


    public function listKeys(string $cacheId, string $dictionaryId, int $limit = null, string $cursor = null, string $prefix = null): \Generator
    {
        try {
            $response = $this->fastlyClient->listDictionaryItems(['service_id' => $cacheId, 'dictionary_id' => $dictionaryId]);
        } catch (ApiException $exception) {
            throw new BadResponseException(
                sprintf(
                    'Fastly deny access listing dictionary %s/%s list items: %s',
                    $cacheId,
                    $dictionaryId,
                    $exception->getMessage()
                ),
                $exception->getRequest(),
                $exception->getResponse(),
                $exception
            );
        }

        foreach ($response as $itemResponse) {
            $data['item_value'] = FastlyItemValue::createByValue($itemResponse->getItemValue());
            yield $itemResponse->getItemKey() => $data;
        }
    }

    public function getKeyValue(string $cacheId, string $dictionaryId, string $key): ?ItemValue
    {
        try {
            $item = $this->fastlyClient->getDictionaryItem(['service_id' => $cacheId, 'dictionary_id' => $dictionaryId, 'dictionary_item_key' => $key]);
        } catch(ApiException $exception) {
            throw new BadResponseException(
                sprintf(
                    'Fastly deny access dictionary %s/%s item %s:',
                    $cacheId,
                    $dictionaryId,
                    $key,
                    $exception->getMessage()
                ),
                $exception->getRequest(),
                $exception->getResponse(),
                $exception
            );
        }

        return FastlyItemValue::createByValue($item->getItemValue());
    }

    public function setKeyValue(string $cacheId, string $dictionaryId, string $key, ItemValue $value, array $options = []): bool
    {
        $options = [
            'service_id' => $cacheId,
            'dictionary_id' => $dictionaryId,
            'dictionary_item_key' => $key,
            'item_key' => $key,
            'item_value' => $value,
        ];

        try {
            $this->fastlyClient->upsertDictionaryItem($options);
        } catch(ApiException $exception) {
            throw new BadRequestException(
                sprintf("Upsert item cannot be done because of error: %s", $exception->getMessage()),
                $exception->getRequest(),
                $exception->getResponse(),
                $exception
            );
        }

        return true;
    }

    public function setMultipleKeysValues(string $serviceId, string $dictionaryId, array $data, array $options = []): bool
    {
        $items = [...$data[self::CREATE_OPERATION], ...$data[self::UPDATE_OPERATION]];

        try {
            foreach($items as $key => $value) {
                $this->setKeyValue($serviceId, $dictionaryId, $key, $value);
            }
        } catch (BadRequestException $exception) {
            return false;
        }

        return true;
    }

    public function deleteKey(string $cacheId, string $dictionaryId, string $key): bool
    {
        try {
            $this->fastlyClient->deleteDictionaryItem(['service_id' => $cacheId, 'dictionary_id' => $dictionaryId, 'dictionary_item_key' => $key]);
        } catch(ApiException $exception) {
            throw new BadRequestException(
                sprintf("Delete item cannot be done because of error: %s", $exception->getMessage()),
                $exception->getRequest(),
                $exception->getResponse(),
                $exception
            );
        }

        return true;
    }

    public function deleteMultipleKeys(string $cacheId, string $dictionaryId, array $keys): bool
    {
        try {
            foreach($keys as $key) {
                $this->deleteKey($cacheId, $dictionaryId, $key);
            }
        } catch (BadRequestException $exception) {
            return false;
        }

        return true;
    }

}
