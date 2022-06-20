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
        $config = $container->get('config')['cdn']['fastly'];
        if (!isset($config['apiToken']) || $config['apiToken'] === ConfigProvider::API_TOKEN_PLACEHOLDER) {
            throw new FastlyApiTokenNotDefinedException();
        }
        if(!isset($config['baseURI'])){
            return new Fastly(new GuzzleAdapter($config['apiToken']));
        }
        return new Fastly(new GuzzleAdapter($config['apiToken']), $config['baseURI']);
    }
}
