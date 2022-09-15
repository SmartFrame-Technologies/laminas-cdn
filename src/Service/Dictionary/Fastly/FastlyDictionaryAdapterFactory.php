<?php

declare(strict_types=1);

namespace Smartframe\Cdn\Service\Dictionary\Fastly;

use Fastly\Api\DictionaryItemApi;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class FastlyDictionaryAdapterFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): FastlyDictionaryAdapter
    {
        return new FastlyDictionaryAdapter(
            $container->get(DictionaryItemApi::class)
        );
    }
}
