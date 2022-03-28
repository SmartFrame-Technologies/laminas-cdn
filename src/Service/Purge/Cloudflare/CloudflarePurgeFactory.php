<?php

declare(strict_types=1);

namespace Smartframe\Cdn\Service\Purge\Cloudflare;

use Cloudflare\API\Endpoints\Zones;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use Smartframe\Cdn\Factory\Cloudflare\CloudflareZonesFactory;

class CloudflarePurgeFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): CloudflarePurge
    {
        return new CloudflarePurge(
            $container->get(Zones::class)
        );
    }
}