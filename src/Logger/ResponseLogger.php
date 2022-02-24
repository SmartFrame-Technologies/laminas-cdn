<?php

namespace Smartframe\Cdn\Logger;

use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

class ResponseLogger implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function __invoke(ResponseInterface $response, array $additionalContext = []): void
    {
        foreach (debug_backtrace() as $backTrace) {
            if ($backTrace['class'] !== __CLASS__) {
                $callerClassName = $backTrace['class'];
                break;
            }
        }

        $this->logger->debug(
            $callerClassName ?? __CLASS__,
            [
                'response' => [
                    'status_code' => $response->getStatusCode(),
                    'reason_phrase' => $response->getReasonPhrase(),
                    'protocol_version' => $response->getProtocolVersion(),
                    'headers' => $response->getHeaders(),
                    'body' => (string)$response->getBody(),
                ]
            ] + $additionalContext
        );
    }
}