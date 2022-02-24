<?php

declare(strict_types=1);

namespace Smartframe\Cdn\Service\Purge\Fastly;

use Fastly\FastlyInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Smartframe\Cdn\Logger\ResponseLogger;

class FastlyPurgeFactory implements FactoryInterface
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): FastlyPurge
    {
        return new FastlyPurge(
            $container->get(FastlyInterface::class),
            $container->get(ResponseLogger::class)
        );
    }
}
