<?php

namespace Smartframe\Cdn\Service\Dictionary\Cloudflare;

use Smartframe\Cdn\Service\Dictionary\AbstractDictionary;
use Smartframe\Cdn\Service\Dictionary\Item;
use Smartframe\Cdn\Service\Dictionary\Items;

class CloudflareDictionary extends AbstractDictionary
{
    public function syncItemsForService(string $serviceName, string $dictionaryName, Items $items): int
    {
        $serviceId = $this->getServiceId($serviceName);
        $dictionaryId = $this->getDictionaryId($serviceName, $dictionaryName);
        $oldItems = $this->listItems($serviceName, $dictionaryName);

        $setItems = [];
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
                    $setItems[$key] = $value->getValue();
                }
            } else {
                $setItems[$key] = $value->getValue();
            }


            unset($oldItems[$key]);
        }

        foreach ($oldItems as $oldKey => $oldValue) {
            $deleteItems[] = $oldKey;
        }

        $result = 0;

        if (!empty($setItems)) {
            $result += $this->client->setMultipleKeysValues($serviceId, $dictionaryId, $setItems) ? count($setItems) : 0;
        }

        if (!empty($deleteItems)) {
            $result += $this->client->deleteMultipleKeys($serviceId, $dictionaryId, $deleteItems) ? count($deleteItems) : 0;
        }

        return $result;
    }

    public function listItems(string $serviceName, string $dictionaryName): Items
    {
        $serviceId = $this->getServiceId($serviceName);
        $dictionaryId = $this->getDictionaryId($serviceName, $dictionaryName);
        $items = $this->client->listKeys($serviceId, $dictionaryId);

        return new CloudflareItems($items);
    }

    public function createItem(string $key, $value = null): Item
    {
        return CloudflareItem::createNew($key, $value);
    }

    public function createItemsCollection(?array $items = null): Items
    {
        return new CloudflareItems($items);
    }
}
