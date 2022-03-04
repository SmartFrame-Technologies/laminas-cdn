<?php

declare(strict_types=1);

namespace SmartframeTest\Cdn\Factory\Cloudflare;


use Cloudflare\API\Endpoints\Zones;
use Generator;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Smartframe\Cdn\ConfigProvider;
use Smartframe\Cdn\Exception\Cloudflare\CloudflareApiTokenNotDefinedException;
use Smartframe\Cdn\Exception\Fastly\FastlyApiTokenNotDefinedException;
use Smartframe\Cdn\Factory\Cloudflare\CloudflareZonesFactory;

class CloudflareZonesFactoryTest extends TestCase
{
    /**
     * @dataProvider configDataProvider
     */
    public function testInvoke(array $config, ?string $expectedExceptionClass = null): void
    {
        $container = $this->getMockBuilder(ContainerInterface::class)->getMock();
        $container->method('get')->willReturn($config);

        $factory = new CloudflareZonesFactory();

        if (isset($expectedExceptionClass)) {
            $this->expectException($expectedExceptionClass);
        }

        $object = $factory($container, Zones::class);

        self::assertInstanceOf(Zones::class, $object);
    }

    public function configDataProvider(): Generator
    {
        yield 'Correct configuration' => [
            'config' => [
                'cdn' => [
                    'cloudflare' => [
                        'apiToken' => 'some-test-token'
                    ]
                ]
            ],
            'expectedExceptionClass' => null
        ];

        yield 'Fastly API token has a placeholder value' => [
            'config' => [
                'cdn' => [
                    'cloudflare' => [
                        'apiToken' => ConfigProvider::API_TOKEN_PLACEHOLDER
                    ]
                ]
            ],
            'expectedExceptionClass' => CloudflareApiTokenNotDefinedException::class
        ];

        yield 'Missing Fastly API token in configuration' => [
            'config' => [
                'cdn' => [
                    'cloudflare' => [
                    ]
                ]
            ],
            'expectedExceptionClass' => CloudflareApiTokenNotDefinedException::class
        ];
    }
}