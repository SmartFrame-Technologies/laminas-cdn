<?php

declare(strict_types=1);

namespace Smartframe\Cdn;

use Smartframe\Cdn\Factory\Fastly\FastlyClientFactory;
use Fastly\Fastly;
use Fastly\FastlyInterface;
use Smartframe\Cdn\Logger\ResponseLogger;
use Smartframe\Cdn\Logger\ResponseLoggerFactory;

class ConfigProvider
{
    public const FASTLY_API_TOKEN_PLACEHOLDER = 'API token placeholder to be overwritten in app config';

    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencyConfig(),
            'cdn' => $this->getCdnConfig(),
        ];
    }

    public function getDependencyConfig(): array
    {
        return [
            'aliases' => [
                FastlyInterface::class => Fastly::class,
            ],
            'factories' => [
                Fastly::class => FastlyClientFactory::class,
                ResponseLogger::class => ResponseLoggerFactory::class,
            ],
        ];
    }

    public function getCdnConfig(): array
    {
        return [
            'fastly' => [
                // this is just a placeholder, API token needs to be defined in app config
                'apiToken' => self::FASTLY_API_TOKEN_PLACEHOLDER,
            ]
        ];
    }
}