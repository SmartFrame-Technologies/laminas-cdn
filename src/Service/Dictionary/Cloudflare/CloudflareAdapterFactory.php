<?php

declare(strict_types=1);

namespace Smartframe\Cdn\Service\Dictionary\Cloudflare;

use Smartframe\Cdn\Service\Dictionary\KeysCache;
use Cloudflare\API\Adapter\Guzzle;
use Cloudflare\API\Auth\APIToken;
use Cloudflare\API\Endpoints\KeyValue;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class CloudflareAdapterFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): CloudflareAdapter
    {
        $config = $container->get('config')['cdn']['cloudflare'];
        $guzzle = new Guzzle(new APIToken($config['apiToken']),$config['baseURI'] ?: null);

        return new CloudflareAdapter(
            new KeyValue($guzzle),
            new KeysCache()
        );
    }
}
