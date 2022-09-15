<?php

declare(strict_types=1);

namespace SmartframeTest\Cdn\Service\Purge\Cloudflare;

use Cloudflare\API\Endpoints\Zones;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Smartframe\Cdn\Service\Purge\Cloudflare\CloudflarePurge;
use Smartframe\Cdn\Service\Purge\Cloudflare\CloudflarePurgeFactory;

class CloudflarePurgeFactoryTest extends TestCase
{
    public function testInvoke(): void
    {
        $container = $this->getMockBuilder(ContainerInterface::class)->getMock();
        $container->method('get')->willReturn(
            $this->createMock(Zones::class)
        );

        $factory = new CloudflarePurgeFactory();

        $object = $factory($container, CloudflarePurge::class);

        self::assertInstanceOf(CloudflarePurge::class, $object);
    }
}