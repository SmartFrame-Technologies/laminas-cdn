# laminas-cdn

The `LaminasCdn` provides integration of the CDN APIs.

[![Build Status](https://github.com/SmartFrame-Technologies/laminas-cdn/actions/workflows/build.yml/badge.svg?branch=git-actions-test)](https://github.com/SmartFrame-Technologies/laminas-cdn/actions/workflows/build.yml/badge.svg?branch=git-actions-test)

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