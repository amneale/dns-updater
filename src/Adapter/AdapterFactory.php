<?php

declare(strict_types=1);

namespace DnsUpdater\Adapter;

use Cloudflare\Api;
use Cloudflare\Zone;
use Cloudflare\Zone\Dns;
use DigitalOceanV2\Adapter\GuzzleHttpAdapter;
use DigitalOceanV2\DigitalOceanV2;

class AdapterFactory
{
    public function build(string $adapter, array $params): Adapter
    {
        switch ($adapter) {
            case DigitalOceanAdapter::NAME:
                $api = new DigitalOceanV2(new GuzzleHttpAdapter(...$params));

                return new DigitalOceanAdapter($api);

            case CloudFlareAdapter::NAME:
                $api = new Api(...$params);

                return new CloudFlareAdapter(new Zone($api), new Dns($api));
        }

        throw new \InvalidArgumentException("Adapter {$adapter} not recognised");
    }
}
