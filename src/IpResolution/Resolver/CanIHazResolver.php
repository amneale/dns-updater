<?php

namespace IpResolution\Resolver;

use GuzzleHttp\ClientInterface;
use IpResolution\Ip;

final class CanIHazResolver implements Resolver
{
    const URI = 'canihazip.com/s';

    /**
     * @var ClientInterface
     */
    private $client;

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
        return new Ip($this->client->request('get', self::URI)->getBody()->getContents());
    }
}
