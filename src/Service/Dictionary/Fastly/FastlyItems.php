<?php

namespace Smartframe\Cdn\Service\Dictionary\Fastly;

use Smartframe\Cdn\Service\Dictionary\Item;
use Smartframe\Cdn\Service\Dictionary\Items;

class FastlyItems extends Items
{
    public function offsetSet($offset, $value): void
    {
        if (is_array($value) && isset($value[Item::KEY_PARAM])) {
            $value = new FastlyItem($value);
        } elseif(!($value instanceof Item)) {
            $value = FastlyItem::createNew($offset, $value);
        }

        $this->items[$offset] = $value;
    }
}
