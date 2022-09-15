<?php

namespace Smartframe\Cdn\Service\Dictionary\Fastly;

use Smartframe\Cdn\Service\Dictionary\ItemValue;

class FastlyItemValue extends ItemValue
{
    public const SEPARATOR = ')|(';
    public const PREFIX = '(';
    public const SUFFIX = ')';

    protected static function implodeOriginalValue(array $array): string
    {
        if (empty($array)) {
            return '';
        }

        return self::PREFIX . implode(self::SEPARATOR, $array) . self::SUFFIX;
    }

    protected static function explodeValue(string $string): array
    {
        $string = ltrim($string, self::PREFIX);
        $string = rtrim($string, self::SUFFIX);

        return explode(self::SEPARATOR, $string);
    }
}
