<?php

namespace Smartframe\Cdn\Service\Dictionary;

use Smartframe\Cdn\Service\Dictionary\Exception\ItemException;

class Item
{
    private \DateTime $createdAt;
    private \DateTime $updatedAt;
    private ?\DateTime $deletedAt;
    private string $serviceId;
    private string $dictionaryId;
    private ?string $key;
    private ItemValue $value;

    public const KEY_PARAM = 'item_key';
    public const VALUE_PARAM = 'item_value';

    public function __construct($data)
    {
        if (!array_key_exists(self::KEY_PARAM, $data) || !array_key_exists(self::VALUE_PARAM, $data)) {
            throw new ItemException(sprintf('Required item variables (item_key, item_value) not set in %s', json_encode($data)));
        }

        $this->createdAt = new \DateTime($data['created_at'] ?? 'now');
        $this->updatedAt = new \DateTime($data['updated_at'] ?? 'now');

        if (!empty($data['deleted_at'])) {
            $this->deletedAt = new \DateTime($data['deleted_at']);
        }

        if (!empty($data['service_id'])) {
            $this->serviceId = (string)$data['service_id'];
        }

        if (!empty($data['dictionary_id'])) {
            $this->dictionaryId = (string) $data['dictionary_id'];
        }

        $this->key = (string) $data[self::KEY_PARAM];

        if (!($data[self::VALUE_PARAM] instanceof ItemValue)) {
            $this->value = $this->createItemValue($data[self::VALUE_PARAM]);
        } else {
            $this->value = $data[self::VALUE_PARAM];
        }

    }

    public static function createNew(string $key, $value = null): self
    {
        return new static([self::KEY_PARAM => $key, self::VALUE_PARAM => $value]);
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function getDeletedAt(): ?\DateTime
    {
        return $this->deletedAt;
    }

    public function getValue(): ItemValue
    {
        return $this->value;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getServiceId(): string
    {
        return $this->serviceId;
    }

    public function getDictionaryId(): string
    {
        return $this->dictionaryId;
    }

    public function setValue($value = null): self
    {
        $this->value = $value;

        return $this;
    }

    public function __toString()
    {
        return (string) $this->value;
    }

    protected function createItemValue($value): ItemValue
    {
        return new ItemValue($value);
    }

}
