<?php

namespace Smartframe\Cdn\Service\Dictionary;

interface DictionaryInterface
{
    public function syncItem(string $dictionaryName, Item $item): int;
    public function syncItems(string $dictionaryName, Items $items): int;
    public function listItems(string $serviceName, string $dictionaryName): Items;
    public function getAvailableServicesForDictionary(string $dictionaryName): array;
    public function getItemsLimit(): ?int;
    public function getNumberOfItemsWarnLevel(): ?int;
    public function createItem(string $key, $value): Item;
    public function createItemsCollection(?array $items = null): Items;
}
