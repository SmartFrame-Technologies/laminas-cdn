<?php

namespace Smartframe\Cdn\Service\Purge\Fastly;

use Fastly\ObjectSerializer;
use GuzzleHttp\Psr7\Request;

class SfModifiedPurgeApi extends \Fastly\Api\PurgeApi
{
    public const PURGE_CACHE_METHOD = 'FASTLYPURGE';

    public function purgeSingleUrlRequest($options): Request
    {
        // unbox the parameters from the associative array
        $fastlySoftPurge = array_key_exists('fastly_soft_purge', $options) ? $options['fastly_soft_purge'] : null;
        $cachedUrl = array_key_exists('cached_url', $options) ? $options['cached_url'] : null;

        // verify the required parameter 'cached_url' is set
        if ($cachedUrl === null || (is_array($cachedUrl) && count($cachedUrl) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $cached_url when calling purgeSingleUrl'
            );
        }

        if (is_array($cachedUrl)) {
            $cachedUrl = reset($cachedUrl);
        }

        $headerParams = [];


        // header params
        if ($fastlySoftPurge !== null) {
            $headerParams['fastly-soft-purge'] = ObjectSerializer::toHeaderValue($fastlySoftPurge);
        }

        $headers = $this->headerSelector->selectHeaders(
            ['application/json'],
            []
        );

        // this endpoint requires HTTP basic authentication
        if (!empty($this->config->getUsername()) || !(empty($this->config->getPassword()))) {
            $headers['Authorization'] =
                'Basic '.
                base64_encode(
                    $this->config->getUsername().
                    ":".
                    $this->config->getPassword()
                );
        }

        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
            $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
            $defaultHeaders,
            $headerParams,
            $headers
        );

        return new Request(
            self::PURGE_CACHE_METHOD,
            $cachedUrl,
            $headers,
            ''
        );
    }
}
