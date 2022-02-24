<?php

declare(strict_types=1);

namespace SmartframeTest\Cdn\Logger;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Smartframe\Cdn\Logger\ResponseLogger;
use Smartframe\Cdn\Logger\ResponseLoggerFactory;

class ResponseLoggerFactoryTest extends TestCase
{
    public function testInvoke(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $factory = new ResponseLoggerFactory();

        $object = $factory($container, ResponseLogger::class);

        self::assertInstanceOf(ResponseLogger::class, $object);
    }
}