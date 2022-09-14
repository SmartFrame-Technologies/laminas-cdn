<?php

declare(strict_types=1);

namespace Smartframe\Cdn;

use Cloudflare\API\Endpoints\Zones;
use Fastly\Api\PurgeApi;
use Smartframe\Cdn\Factory\Cloudflare\CloudflareZonesFactory;
use Smartframe\Cdn\Factory\Fastly\PurgeApiFactory;
use Smartframe\Cdn\Logger\ResponseLogger;
use Smartframe\Cdn\Logger\ResponseLoggerFactory;
use Smartframe\Cdn\Service\Purge\Cloudflare\CloudflarePurge;
use Smartframe\Cdn\Service\Purge\Cloudflare\CloudflarePurgeFactory;
use Smartframe\Cdn\Service\Purge\Fastly\FastlyPurge;
use Smartframe\Cdn\Service\Purge\Fastly\FastlyPurgeFactory;

class ConfigProvider
{
    public const API_TOKEN_PLACEHOLDER = 'API token placeholder to be overwritten in app config';

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
            'factories' => [
                // fastly
                FastlyPurge::class => FastlyPurgeFactory::class,
                PurgeApi::class => PurgeApiFactory::class,
                // cloudflare
                CloudflarePurge::class => CloudflarePurgeFactory::class,
                Zones::class => CloudflareZonesFactory::class,
                // utilities
                ResponseLogger::class => ResponseLoggerFactory::class,
            ],
        ];
    }

    public function getCdnConfig(): array
    {
        return [
            'fastly' => [
                // this is just a placeholder, API token needs to be defined in app config
                'apiToken' => self::API_TOKEN_PLACEHOLDER,
            ],
            'cloudflare' => [
                // this is just a placeholder, API token needs to be defined in app config
                'apiToken' => self::API_TOKEN_PLACEHOLDER,
                'baseURI' => null,
            ],
        ];
    }
}