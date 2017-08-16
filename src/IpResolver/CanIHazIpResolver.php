<?php

namespace DnsUpdater\IpResolver;

use DnsUpdater\IpAddress;
use GuzzleHttp\ClientInterface;

final class CanIHazIpResolver implements IpResolver
{
    const URI = 'canihazip.com/s';

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var IpAddress
     */
    private $ipAddress;

    /**
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @inheritdoc
     */
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
