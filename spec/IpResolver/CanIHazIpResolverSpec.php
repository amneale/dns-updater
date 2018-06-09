<?php

namespace spec\DnsUpdater\IpResolver;

use DnsUpdater\IpResolver\CanIHazIpResolver;
use DnsUpdater\IpResolver\IpResolver;
use GuzzleHttp\ClientInterface;
use PhpSpec\ObjectBehavior;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class CanIHazIpResolverSpec extends ObjectBehavior
{
    const TEST_IP = '192.168.0.1';

    public function let(ClientInterface $client): void
    {
        $this->beConstructedWith($client);
    }

    public function it_implements_ip_resolver(): void
    {
        $this->shouldImplement(IpResolver::class);
    }

    public function it_gets_an_ip(ClientInterface $client, ResponseInterface $response, StreamInterface $stream): void
    {
        $client->request('get', CanIHazIpResolver::URI)->willReturn($response);
        $response->getBody()->willReturn($stream);
        $stream->getContents()->willReturn(self::TEST_IP);

        $this->getIpAddress()->__toString()->shouldReturn(self::TEST_IP);
    }
}
