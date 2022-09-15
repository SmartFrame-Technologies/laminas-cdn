<?php

namespace Smartframe\Cdn\Service\Dictionary\Cloudflare;

use Smartframe\Cdn\Service\Dictionary\ItemValue;
use Laminas\Json\Json;

class CloudflareItemValue extends ItemValue
{
    public function __construct($originValue = null)
    {
        if (is_object($originValue)) {
            $value = json_encode($originValue);
        } else {
            $value = $originValue;
        }

        parent::__construct($value);

        $this->originValue = $originValue;
    }

    protected static function implodeOriginalValue(array $array): string
    {
        if (empty($array)) {
            return '';
        }

        return Json::encode($array);
    }

    protected static function explodeValue(string $string): array
    {
        return Json::decode($string, Json::TYPE_ARRAY);
    }
}
