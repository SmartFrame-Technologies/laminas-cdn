<?php

namespace Smartframe\Cdn\Service\Dictionary;

use Smartframe\Cdn\Service\Dictionary\Exception\WrongDictionaryException;
use Smartframe\Cdn\Service\Dictionary\Exception\WrongServiceException;

abstract class AbstractDictionary implements DictionaryInterface
{
    protected AdapterInterface $client;
    protected array $servicesIds;
    protected array $dictionariesIds;
    protected ?int $itemLimits;
    protected ?int $numberOfItemsWarnLevel;

    public function __construct(AdapterInterface $client, array $servicesIds, array $dictionariesIds, int $itemLimits = null, int $numberOfItemsWarnLevel = null)
    {
        $this->client = $client;
        $this->servicesIds = $servicesIds;
        $this->dictionariesIds = $dictionariesIds;
        $this->itemLimits = $itemLimits;
        $this->numberOfItemsWarnLevel = $numberOfItemsWarnLevel;
    }

    public function getItemsLimit(): ?int
    {
        return $this->itemLimits > 0 ? $this->itemLimits : null;
    }

    public function getNumberOfItemsWarnLevel(): ?int
    {
        return $this->numberOfItemsWarnLevel > 0 ? $this->numberOfItemsWarnLevel : null;
    }

    abstract public function syncItemsForService(string $serviceName, string $dictionaryName, Items $items): int;
    abstract public function listItems(string $serviceName, string $dictionaryName): Items;

    public function syncItem(string $dictionaryName, Item $item): int
    {
        $servicesNames = $this->getAvailableServicesForDictionary($dictionaryName);

        $result = 0;
        foreach ($servicesNames as $serviceName) {
            $result += $this->syncItemForService($serviceName, $dictionaryName, $item) ? 1 : 0;
        }

        return $result;
    }


    public function syncItemForService(string $serviceName, string $dictionaryName, Item $item): bool
    {
        $items = $this->listItems($serviceName, $dictionaryName);

        if (!$item->getValue()->isEmpty()) {
            if (!isset($items[$item->getKey()]) || $items[$item->getKey()]->getValue()->getValue() !== $item->getValue()->getValue()) {
                return $this->upsertItemForService($serviceName, $dictionaryName, $item);
            }
        } elseif (isset($items[$item->getKey()])) {
            return $this->deleteItemForService($serviceName, $dictionaryName, $item);
        }

        return false;
    }

    public function syncItems(string $dictionaryName, Items $items): int
    {
        $servicesNames = $this->getAvailableServicesForDictionary($dictionaryName);

        $result = 0;
        foreach ($servicesNames as $serviceName) {
            $result += $this->syncItemsForService($serviceName, $dictionaryName, $items);
        }

        return $result;
    }

    public function deleteItemForService(string $serviceName, string $dictionaryName, Item $item): bool
    {
        $serviceId = $this->getServiceId($serviceName);
        $dictionaryId = $this->getDictionaryId($serviceName, $dictionaryName);

        return $this->getClient()->deleteKey($serviceId, $dictionaryId, $item->getKey());
    }

    public function upsertItemForService(string $serviceName, string $dictionaryName, Item $item): bool
    {
        $serviceId = $this->getServiceId($serviceName);
        $dictionaryId = $this->getDictionaryId($serviceName, $dictionaryName);

        return $this->getClient()->setKeyValue($serviceId, $dictionaryId, $item->getKey(), $item->getValue());
    }

    public function getServiceId(string $serviceName)
    {
        if (empty($this->servicesIds[$serviceName])) {
            throw new WrongServiceException(sprintf('Service %s not found in config', $serviceName));
        }

        return $this->servicesIds[$serviceName];
    }

    public function getDictionaryId(string $serviceName, string $dictionaryName)
    {
        if (empty($this->dictionariesIds[$serviceName][$dictionaryName])) {
            throw new WrongDictionaryException(sprintf('Dictionary %s/%s not found in config', $serviceName, $dictionaryName));
        }

        return $this->dictionariesIds[$serviceName][$dictionaryName];
    }

    public function getAvailableServicesForDictionary(string $dictionaryName): array
    {
        $servicesNames = [];

        foreach ($this->dictionariesIds as $serviceName => $dictionariesNames) {
            if (isset($dictionariesNames[$dictionaryName])) {
                $servicesNames[] = $serviceName;
            }
        }

        return $servicesNames;
    }

    public function getClient(): AdapterInterface
    {
        return $this->client;
    }
}
