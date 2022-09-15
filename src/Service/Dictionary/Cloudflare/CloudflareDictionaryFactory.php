<?php

declare(strict_types=1);

namespace Smartframe\Cdn\Service\Dictionary\Cloudflare;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class CloudflareDictionaryFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): CloudflareDictionary
    {
        return new CloudflareDictionary(
            $container->get(CloudflareAdapter::class),
            $container->get('config')['cdn']['cloudflare']['accounts'],
            $container->get('config')['cdn']['cloudflare']['namespaces'],
            null,
            null
        );
    }
}
