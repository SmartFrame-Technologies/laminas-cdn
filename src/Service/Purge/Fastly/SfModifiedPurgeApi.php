<?php

namespace Smartframe\Cdn\Service\Purge\Fastly;

use GuzzleHttp\Psr7\Request;

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
        $url = reset($headers['host']);
        if(!preg_match('/^(https?:\/\/)?(.*?)\/(.*)$/i', $url, $matches)) {
            return $request;
        }

        $host = $matches[2];
        $headers['host'] = $host;
        unset($headers['Accept'], $headers['Content-Type']);

        return new Request(
            self::PURGE_CACHE_METHOD,
            $url,
            $headers,
            $request->getBody()
        );
    }
}