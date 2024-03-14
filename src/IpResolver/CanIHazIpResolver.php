<?php

declare(strict_types=1);

namespace DnsUpdater\IpResolver;

use DnsUpdater\Value\IpAddress;
use GuzzleHttp\ClientInterface;

final class CanIHazIpResolver implements IpResolver
{
    public const URI = 'canihazip.com/s';

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var IpAddress
     */
    private $ipAddress;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

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
