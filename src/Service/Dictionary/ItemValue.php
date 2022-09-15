<?php

namespace Smartframe\Cdn\Service\Dictionary;

class ItemValue
{
    protected $originValue;
    protected string $value;

    public static function createByValue($value): self
    {
        if (is_numeric($value)) {
            $originValue = (int) $value;
        } elseif (is_string($value)) {
            $originValue = static::explodeValue($value);
        } else {
            $originValue = $value;
        }

        return new static($originValue);
    }

    public function __construct($originValue = null)
    {
        $this->originValue = $originValue;

        if (!is_array($originValue)) {
            $originValue = [$originValue];
        }

        $this->value = static::implodeOriginalValue($originValue);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getOriginValue()
    {
        return $this->originValue;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function isEmpty(): bool
    {
        return empty($this->originValue);
    }

    protected static function implodeOriginalValue(array $array): string
    {
        return implode(', ', $array);
    }

    protected static function explodeValue(string $string): array
    {
        return explode(', ', $string);
    }
}
