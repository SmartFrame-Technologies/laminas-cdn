<?php

declare(strict_types=1);

namespace Smartframe\Cdn\Factory\Fastly;

use Fastly\Api\PurgeApi;
use Fastly\Configuration;
use GuzzleHttp\Client;
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
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): PurgeApi
    {
        $config = $container->get('config')['cdn']['fastly'] ?? [];
        if (!isset($config['apiToken']) || $config['apiToken'] === ConfigProvider::API_TOKEN_PLACEHOLDER) {
            throw new FastlyApiTokenNotDefinedException();
        }

        $fastlyConfig = new Configuration();
        $fastlyConfig->setAccessToken($config['apiToken']);

        if(isset($config['baseURI'])){
            $fastlyConfig->setHost($config['baseURI']);
        }

        return new PurgeApi(
            new Client(),
            $fastlyConfig
        );
    }
}
