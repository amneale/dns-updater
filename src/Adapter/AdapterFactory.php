<?php

declare(strict_types=1);

namespace DnsUpdater\Adapter;

use Cloudflare\Api;
use Cloudflare\Zone;
use Cloudflare\Zone\Dns;
use DigitalOceanV2\Client;

class AdapterFactory
{
    public function build(string $adapter, array $params): Adapter
    {
        switch ($adapter) {
            case DigitalOceanAdapter::NAME:
                if (empty($params[0])) {
                    throw new \InvalidArgumentException('Access token parameter is missing');
                }

                $api = new Client();
                $api->authenticate((string) $params[0]);

                return new DigitalOceanAdapter($api);

            case CloudFlareAdapter::NAME:
                $api = new Api(...$params);

                return new CloudFlareAdapter(new Zone($api), new Dns($api));
        }

        throw new \InvalidArgumentException("Adapter {$adapter} not recognised");
    }
}
