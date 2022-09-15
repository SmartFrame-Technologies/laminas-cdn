<?php

declare(strict_types=1);

namespace Smartframe\Cdn\Service\Dictionary\Fastly;

use Fastly\FastlyInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class FastlyDictionaryFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): FastlyDictionary
    {
        return new FastlyDictionary(
            $container->get(FastlyDictionaryAdapter::class),
            $container->get('config')['cdn']['fastly']['services'],
            $container->get('config')['cdn']['fastly']['dictionaries'],
            $container->get('config')['cdn']['fastly']['limits']['dictionaries']['items_max'],
            $container->get('config')['cdn']['fastly']['limits']['dictionaries']['items_warn']
        );
    }
}
