<?php

namespace Smartframe\Cdn\Service\Dictionary\Fastly;

use Smartframe\Cdn\Service\Dictionary\Item;
use Smartframe\Cdn\Service\Dictionary\ItemValue;

class FastlyItem extends Item
{
    protected function createItemValue($value): ItemValue
    {
        return new FastlyItemValue($value);
    }
}
