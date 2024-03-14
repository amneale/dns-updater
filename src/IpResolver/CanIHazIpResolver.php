<?php

declare(strict_types=1);

namespace DnsUpdater\IpResolver;

use DnsUpdater\Value\IpAddress;
use GuzzleHttp\ClientInterface;

final class CanIHazIpResolver implements IpResolver
{
    public const URI = 'canihazip.com/s';

    private IpAddress $ipAddress;

    public function __construct(private ClientInterface $client) {}

    public function getIpAddress(): IpAddress
    {
        if (!isset($this->ipAddress)) {
            $this->ipAddress = new IpAddress(
                $this->client->request('get', self::URI)->getBody()->getContents()
            );
        }

        return $this->ipAddress;
    }
}
