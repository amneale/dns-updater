<?php

namespace DnsUpdater\Service;

use DnsUpdater\Command\Service\IpResolver;
use DnsUpdater\Ip;
use GuzzleHttp\ClientInterface;

final class CanIHazIpResolver implements IpResolver
{
    const URI = 'canihazip.com/s';

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var Ip
     */
    private $ip;

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
    public function getIp(): Ip
    {
        if (!isset($this->ip)) {
            $this->ip = new Ip(
                $this->client->request('get', self::URI)->getBody()->getContents()
            );
        }

        return $this->ip;
    }
}
