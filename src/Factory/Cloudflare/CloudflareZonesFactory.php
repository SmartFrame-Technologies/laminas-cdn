<?php

declare(strict_types=1);

namespace Smartframe\Cdn\Factory\Cloudflare;

use Cloudflare\API\Adapter\Guzzle;
use Cloudflare\API\Auth\APIToken;
use Cloudflare\API\Endpoints\Zones;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use Smartframe\Cdn\ConfigProvider;
use Smartframe\Cdn\Exception\Cloudflare\CloudflareApiTokenNotDefinedException;


class CloudflareZonesFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): Zones
    {
        $config = $container->get('config')['cdn']['cloudflare'];

        if (is_null($config['apiToken']) || $config['apiToken'] === ConfigProvider::API_TOKEN_PLACEHOLDER) {
            throw new CloudflareApiTokenNotDefinedException();
        }

        return new Zones(
            new Guzzle(new APIToken($config['apiToken']), $config['baseURI'] ?? null)
        );
    }
}
