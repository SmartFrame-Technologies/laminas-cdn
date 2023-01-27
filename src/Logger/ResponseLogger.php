<?php

namespace Smartframe\Cdn\Logger;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

class ResponseLogger implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function __invoke(\JsonSerializable $response, array $additionalContext = []): void
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
                'response' => json_encode($response)
            ] + $additionalContext
        );
    }
}