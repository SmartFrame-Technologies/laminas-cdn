<?php

declare(strict_types=1);

namespace SmartframeTest\Cdn\Service\Purge\Cloudflare;

use Cloudflare\API\Endpoints\Zones;
use PHPUnit\Framework\TestCase;
use Smartframe\Cdn\Exception\WildcardUrlNotSupportedException;
use Smartframe\Cdn\Service\Purge\Cloudflare\CloudflarePurge;

class CloudflarePurgeTest extends TestCase
{
    private const CLOUDFLARE_ZONE_ID = 'unit-tests';
    private const TEST_URL = 'https://some-address.com/something';
    private const TEST_URL_WITH_WILDCARD = 'https://some-address.com/something/*';
    private const TEST_CACHE_TAG = 'some-cache-tag';

    private Zones $zonesEndpoint;

    public function setUp(): void
    {
        parent::setUp();

        $this->zonesEndpoint = $this->createMock(Zones::class);
    }

    public function testPurgeUrlWithWildcard(): void
    {
        $this->expectException(WildcardUrlNotSupportedException::class);

        $cloudflarePurge = new CloudflarePurge($this->zonesEndpoint);
        $cloudflarePurge->url(self::CLOUDFLARE_ZONE_ID, self::TEST_URL_WITH_WILDCARD);
    }

    public function testPurgeUrl(): void
    {
        $this->zonesEndpoint
            ->expects(self::once())
            ->method('cachePurge')
            ->with(self::CLOUDFLARE_ZONE_ID, [self::TEST_URL]);

        $cloudflarePurge = new CloudflarePurge($this->zonesEndpoint);
        $cloudflarePurge->url(self::CLOUDFLARE_ZONE_ID, self::TEST_URL);
    }

    public function testPurgeKey(): void
    {
        $this->zonesEndpoint
            ->expects(self::once())
            ->method('cachePurge')
            ->with(self::CLOUDFLARE_ZONE_ID, [], [self::TEST_CACHE_TAG]);

        $cloudflarePurge = new CloudflarePurge($this->zonesEndpoint);
        $cloudflarePurge->key(self::CLOUDFLARE_ZONE_ID, self::TEST_CACHE_TAG);
    }

    public function testPurgeAll(): void
    {
        $this->zonesEndpoint
            ->expects(self::once())
            ->method('cachePurgeEverything');

        $cloudflarePurge = new CloudflarePurge($this->zonesEndpoint);
        $cloudflarePurge->all(self::CLOUDFLARE_ZONE_ID);
    }
}