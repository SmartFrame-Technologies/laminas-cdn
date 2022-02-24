<?php

declare(strict_types=1);

namespace Smartframe\Cdn\Logger;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class ResponseLoggerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): ResponseLogger
    {
        return new ResponseLogger();
    }
}
