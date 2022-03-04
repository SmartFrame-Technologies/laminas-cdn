<?php

declare(strict_types=1);

namespace Smartframe\Cdn\Factory\Fastly;

use Fastly\Adapter\Guzzle\GuzzleAdapter;
use Fastly\Fastly;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Smartframe\Cdn\ConfigProvider;
use Smartframe\Cdn\Exception\Fastly\FastlyApiTokenNotDefinedException;

class FastlyClientFactory implements FactoryInterface
{
    /**
     * @throws FastlyApiTokenNotDefinedException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): Fastly
    {
        $apiToken = $container->get('config')['cdn']['fastly']['apiToken'] ?? null;

        if (is_null($apiToken) || $apiToken === ConfigProvider::API_TOKEN_PLACEHOLDER) {
            throw new FastlyApiTokenNotDefinedException();
        }

        return new Fastly(new GuzzleAdapter($apiToken));
    }
}
