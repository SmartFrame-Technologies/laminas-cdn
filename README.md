# laminas-cdn

The `LaminasCdn` provides integration of the CDN APIs.

[![Build Status](https://travis-ci.com/SmartFrame-Technologies/laminas-cdn.svg?branch=master)](https://travis-ci.com/SmartFrame-Technologies/laminas-cdn)
[![Coverage Status](https://coveralls.io/repos/github/SmartFrame-Technologies/laminas-cdn/badge.svg?branch=master)](https://coveralls.io/github/SmartFrame-Technologies/laminas-cdn?branch=master)

## Installation

Install the latest version with

```bash
$ composer require smartframe-technologies/laminas-cdn
```

## Configuration

Start by creating a logging configuration file (i.e. `config/autoload/cdn.global.php`) with minimal configration

If are you using [ConfigAggregator](https://github.com/laminas/laminas-config-aggregator/) library already have
defined `ConfigProvider`\
More information in [ConfigProviders](https://docs.laminas.dev/laminas-config-aggregator/config-providers/) section

## Minimal config settings

```php
<?php
return [
    'cdn' => [
        'fastly' => [
            'apiToken' => 'your-api-token-goes-here'
        ],
    ]
];
```

## License

See the [LICENSE](LICENSE.md) file for license rights and limitations (Apache license 2.0).