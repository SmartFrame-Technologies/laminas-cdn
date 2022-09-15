<?php

namespace Smartframe\Cdn\Service\Dictionary\Fastly;

use Smartframe\Cdn\Service\Dictionary\AbstractDictionary;
use Smartframe\Cdn\Service\Dictionary\Item;
use Smartframe\Cdn\Service\Dictionary\Items;

class FastlyDictionary extends AbstractDictionary
{
    public function syncItemsForService(string $serviceName, string $dictionaryName, Items $items): int
    {
        $serviceId = $this->getServiceId($serviceName);
        $dictionaryId = $this->getDictionaryId($serviceName, $dictionaryName);
        $oldItems = $this->listItems($serviceName, $dictionaryName);

        $createItems = [];
        $updateItems = [];
        $deleteItems = [];
        foreach ($items as $item) {
            $key = $item->getKey();
            $value = $item->getValue();

            if ($value->isEmpty()) {
                if (isset($oldItems[$key])) {
                    $deleteItems[] = $key;
                }
            } elseif (isset($oldItems[$key])) {
                if ($oldItems[$key]->getValue() !== $value->getValue()) {
                    $updateItems[$key] = $value->getValue();
                }
            } else {
                $createItems[$key] = $value->getValue();
            }

            unset($oldItems[$key]);
        }

        foreach ($oldItems as $oldKey => $oldValue) {
            $deleteItems[] = $oldKey;
        }

        $result = 0;

        if (!empty($createItems) || !empty($updateItems)) {
            $result += $this->getClient()->setMultipleKeysValues($serviceId, $dictionaryId, [
                    FastlyDictionaryAdapter::CREATE_OPERATION => $createItems,
                    FastlyDictionaryAdapter::UPDATE_OPERATION => $updateItems
                ]
            ) ? count($createItems) + count($updateItems) : 0;
        }

        if (!empty($deleteItems)) {
            $result += $this->getClient()->deleteMultipleKeys($serviceId, $dictionaryId, $deleteItems) ? count($deleteItems) : 0;
        }

        return $result;
    }

    public function listItems(string $serviceName, string $dictionaryName): FastlyItems
    {
        $serviceId = $this->getServiceId($serviceName);
        $dictionaryId = $this->getDictionaryId($serviceName, $dictionaryName);
        $itemsArray = $this->getClient()->listKeys($serviceId, $dictionaryId);

        return new FastlyItems($itemsArray);
    }

    public function createItem(string $key, $value = null): Item
    {
        return FastlyItem::createNew($key, $value);
    }

    public function createItemsCollection(?array $items = null): Items
    {
        return new FastlyItems($items);
    }
}
