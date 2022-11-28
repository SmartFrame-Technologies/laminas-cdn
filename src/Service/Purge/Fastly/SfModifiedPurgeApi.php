<?php

namespace Smartframe\Cdn\Service\Purge\Fastly;

use GuzzleHttp\Psr7\Request;
use Smartframe\Cdn\Exception\GivenUrlNotSupportedException;

class SfModifiedPurgeApi extends \Fastly\Api\PurgeApi
{
    public const PURGE_CACHE_METHOD = 'FASTLYPURGE';

    public function purgeSingleUrlRequest($options)
    {
        /** @var Request $request */
        $request = parent::purgeSingleUrlRequest($options);

        if ($request->getMethod() === 'GET') {
            return $this->modifyFastlyPurgeRequest($request);
        } else {
            return $request;
        }

    }

    protected function modifyFastlyPurgeRequest(Request $request): Request
    {
        $headers = $request->getHeaders();
        $url = $headers['host'];
        if(!preg_match('/^(https?:\/\/)?(.*?)\/(.*)$/i', $url, $matches)) {
            return $request;
        }

        $host = $matches[2];
        $cachedUrl = $matches[2] . '/' . $matches[3];
        $headers['host'] = $host;

        return new Request(
            self::PURGE_CACHE_METHOD,
            $url,
            $headers,
            $request->getBody()
        );
    }
}