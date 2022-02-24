<?php

declare(strict_types=1);

namespace SmartframeTest\Cdn\Service\Fastly;

use Fastly\FastlyInterface;
use Fig\Http\Message\StatusCodeInterface;
use Generator;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Log\LoggerInterface;
use Smartframe\Cdn\Exception\WildcardUrlNotSupportedException;
use Smartframe\Cdn\Logger\ResponseLogger;
use Smartframe\Cdn\Service\Purge\Fastly\FastlyPurge;

class FastlyPurgeTest extends TestCase
{
    private const FASTLY_SERVICE_ID = 'unit-tests';
    private const TEST_URL = 'https://some-address.com/something';
    private const TEST_URL_WITH_WILDCARD = 'https://some-address.com/something/*';
    private const TEST_SURROGATE_KEY = 'some-surrogate-key';

    private FastlyInterface $fastlyClient;
    private FastlyPurge $fastlyPurge;

    public function setUp(): void
    {
        parent::setUp();

        $this->fastlyClient = $this->createMock(FastlyInterface::class);
        $this->responseLogger = $this->createMock(ResponseLogger::class);

        $this->fastlyPurge = new FastlyPurge($this->fastlyClient, $this->responseLogger);
    }

    public function testPurgeUrlWithWildcard(): void
    {
        $this->expectException(WildcardUrlNotSupportedException::class);

        $this->fastlyPurge->url(self::FASTLY_SERVICE_ID, self::TEST_URL_WITH_WILDCARD);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testPurgeUrl(int $responseStatusCode, bool $expectedResult = false): void
    {
        $response = $this->prepareMockedResponse($responseStatusCode);

        $this->fastlyClient->method('purge')->willReturn($response);

        $actualResult = $this->fastlyPurge->url(self::FASTLY_SERVICE_ID, self::TEST_URL);

        self::assertSame($expectedResult, $actualResult);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testPurgeKey(int $responseStatusCode, bool $expectedResult = false): void
    {
        $response = $this->prepareMockedResponse($responseStatusCode);

        $this->fastlyClient->method('purgeKey')->willReturn($response);

        $actualResult = $this->fastlyPurge->key(self::FASTLY_SERVICE_ID, self::TEST_SURROGATE_KEY);

        self::assertSame($expectedResult, $actualResult);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testPurgeAll(int $responseStatusCode, bool $expectedResult = false): void
    {
        $response = $this->prepareMockedResponse($responseStatusCode);

        $this->fastlyClient->method('purgeAll')->willReturn($response);

        $actualResult = $this->fastlyPurge->all(self::FASTLY_SERVICE_ID);

        self::assertSame($expectedResult, $actualResult);
    }

    public function dataProvider(): Generator
    {
        yield 'OK response' => [
            StatusCodeInterface::STATUS_OK,
            true
        ];

        yield 'FORBIDDEN response' => [
            StatusCodeInterface::STATUS_FORBIDDEN,
            false
        ];
    }

    private function prepareMockedResponse(int $responseStatusCode): ResponseInterface
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn($this->createMock(StreamInterface::class));
        $response->method('getStatusCode')->willReturn($responseStatusCode);
        return $response;
    }
}