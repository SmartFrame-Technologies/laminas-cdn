<?php

declare(strict_types=1);

namespace SmartframeTest\Cdn\Service\Fastly;

use Fastly\Api\PurgeApi;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Smartframe\Cdn\Logger\ResponseLogger;
use Smartframe\Cdn\Service\Purge\Fastly\FastlyPurge;
use Smartframe\Cdn\Service\Purge\Fastly\FastlyPurgeFactory;

class FastlyPurgeFactoryTest extends TestCase
{
    public function testInvoke(): void
    {
        $fastlyClient = $this->createMock(PurgeApi::class);
        $responseLogger = $this->createMock(ResponseLogger::class);

        $container = $this->getMockBuilder(ContainerInterface::class)->getMock();
        $container->method('get')->willReturn($fastlyClient, $responseLogger);

        $factory = new FastlyPurgeFactory();

        $object = $factory($container, FastlyPurge::class);

        self::assertInstanceOf(FastlyPurge::class, $object);
    }
}