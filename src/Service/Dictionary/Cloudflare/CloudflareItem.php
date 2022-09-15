<?php

namespace Smartframe\Cdn\Service\Dictionary\Cloudflare;

use Smartframe\Cdn\Service\Dictionary\Item;
use Smartframe\Cdn\Service\Dictionary\ItemValue;

class CloudflareItem extends Item
{
    protected function createItemValue($value): ItemValue
    {
        return new CloudflareItemValue($value);
    }
}
