<?php

namespace Smartframe\Cdn\Service\Dictionary;

class Items implements \ArrayAccess, \IteratorAggregate, \Countable
{
    protected array $items = [];

    public function __construct(?iterable $items = null)
    {
        if (!empty($items)) {
            foreach($items as $key => $item) {
                if (is_array($item) && isset($item[Item::KEY_PARAM])) {
                    $this->offsetSet($item[Item::KEY_PARAM], $item);
                } else {
                    $this->offsetSet($key, $item);
                }
            }
        }
    }


    public function offsetExists($offset): bool
    {
        return isset($this->items[$offset]);
    }

    public function offsetGet($offset): ?Item
    {
        return $this->items[$offset] ?? null;
    }

    public function offsetSet($offset, $value): void
    {
        if (is_array($value) && isset($value[Item::KEY_PARAM])) {
            $value = new Item($value);
        } elseif(!($value instanceof Item)) {
            $value = Item::createNew($offset, $value);
        }

        $this->items[$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        unset($this->items[$offset]);
    }

    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->items);
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function getKeys(): array
    {
        return array_keys($this->items);
    }

    public function toArray(): array
    {
        $array = [];

        foreach($this->items as $key => $item) {
            $array[$key] = (string) $item;
        }

        return $array;
    }
}
