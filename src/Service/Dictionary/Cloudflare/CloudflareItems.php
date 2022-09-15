<?php

namespace Smartframe\Cdn\Service\Dictionary\Cloudflare;

use Smartframe\Cdn\Service\Dictionary\Item;
use Smartframe\Cdn\Service\Dictionary\Items;

class CloudflareItems extends Items
{
    public function offsetSet($offset, $value): void
    {
        if (is_array($value) && isset($value[Item::KEY_PARAM])) {
            $value = new CloudflareItem($value);
        } elseif(!($value instanceof Item)) {
            $value = CloudflareItem::createNew($offset, $value);
        }

        $this->items[$offset] = $value;
    }
}
