<?php

declare(strict_types=1);

namespace Smartframe\Cdn;

use Smartframe\Cdn\Service\Dictionary\Cloudflare\CloudflareAdapter;
use Smartframe\Cdn\Service\Dictionary\Cloudflare\CloudflareAdapterFactory;
use Smartframe\Cdn\Service\Dictionary\Cloudflare\CloudflareDictionary as CloudflareDictionary;
use Smartframe\Cdn\Service\Dictionary\Cloudflare\CloudflareDictionaryFactory as CloudflareDictionaryFactory;
use Smartframe\Cdn\Service\Dictionary\Fastly\FastlyDictionary;
use Smartframe\Cdn\Service\Dictionary\Fastly\FastlyDictionaryAdapter;
use Smartframe\Cdn\Service\Dictionary\Fastly\FastlyDictionaryAdapterFactory;
use Smartframe\Cdn\Service\Dictionary\Fastly\FastlyDictionaryFactory;
use Smartframe\Cdn\Factory\Cloudflare\CloudflareZonesFactory;
use Smartframe\Cdn\Factory\Fastly\FastlyApiFactory;
use Smartframe\Cdn\Logger\ResponseLogger;
use Smartframe\Cdn\Logger\ResponseLoggerFactory;
use Smartframe\Cdn\Service\Purge\Cloudflare\CloudflarePurge;
use Smartframe\Cdn\Service\Purge\Cloudflare\CloudflarePurgeFactory;
use Smartframe\Cdn\Service\Purge\Fastly\FastlyPurge;
use Smartframe\Cdn\Service\Purge\Fastly\FastlyPurgeFactory;
use Cloudflare\API\Endpoints\Zones;
use Fastly\Api\DictionaryItemApi;
use Fastly\Api\PurgeApi;
use Smartframe\Cdn\Service\Purge\Fastly\SfModifiedPurgeApi;

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
                FastlyDictionary::class => FastlyDictionaryFactory::class,
                FastlyDictionaryAdapter::class => FastlyDictionaryAdapterFactory::class,
                SfModifiedPurgeApi::class => FastlyApiFactory::class,
                PurgeApi::class => FastlyApiFactory::class,
                DictionaryItemApi::class => FastlyApiFactory::class,
                // cloudflare
                CloudflarePurge::class => CloudflarePurgeFactory::class,
                CloudflareDictionary::class => CloudflareDictionaryFactory::class,
                CloudflareAdapter::class => CloudflareAdapterFactory::class,
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