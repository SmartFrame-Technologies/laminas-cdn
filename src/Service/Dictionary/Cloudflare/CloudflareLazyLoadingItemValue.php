<?php

namespace Smartframe\Cdn\Service\Dictionary\Cloudflare;

use Cloudflare\API\Adapter\ResponseException;

class CloudflareLazyLoadingItemValue extends CloudflareItemValue
{
    protected $loadOriginalItemValueCallback = null;

    public static function createByValueCallback(callable $loadOriginalItemValueCallback): self
    {
        return new static($loadOriginalItemValueCallback);
    }

    public function __construct($originValueOrCallback = null)
    {
        if (is_callable($originValueOrCallback)) {
            $this->loadOriginalItemValueCallback = $originValueOrCallback;
        } else {
            parent::__construct($originValueOrCallback);
        }
    }

    public function __toString(): string
    {
        $this->loadValue();
        return parent::__toString();
    }

    public function isEmpty(): bool
    {
        $this->loadValue();
        return parent::isEmpty();
    }

    public function getOriginValue()
    {
        $this->loadValue();
        return parent::getOriginValue();
    }

    public function getValue(): string
    {
        $this->loadValue();
        return parent::getValue();
    }

    protected function loadValue(): void
    {
        if ($this->loadOriginalItemValueCallback !== null) {
            try {
                $originalValue = ($this->loadOriginalItemValueCallback)();
            } catch(ResponseException $exception) {
                $originalValue = null;
            }
            parent::__construct($originalValue);
            $this->loadOriginalItemValueCallback = null;
        }
    }
}
