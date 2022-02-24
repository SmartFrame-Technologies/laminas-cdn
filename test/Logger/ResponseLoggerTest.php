<?php

namespace SmartframeTest\Cdn\Logger;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Smartframe\Cdn\Logger\ResponseLogger;

class ResponseLoggerTest extends TestCase
{
    public function testInvoke()
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(self::once())->method('debug');

        $responseLogger = new ResponseLogger();
        $responseLogger->setLogger($logger);

        $response = $this->createMock(ResponseInterface::class);

        $responseLogger($response, []);
    }
}