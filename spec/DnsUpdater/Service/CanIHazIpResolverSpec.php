<?php

namespace spec\DnsUpdater\Service;

use DnsUpdater\Command\Service\IpResolver;
use DnsUpdater\Service\CanIHazIpResolver;
use GuzzleHttp\ClientInterface;
use PhpSpec\ObjectBehavior;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class CanIHazIpResolverSpec extends ObjectBehavior
{
    const TEST_IP = '192.168.0.1';

    function let(ClientInterface $client)
    {
        $this->beConstructedWith($client);
    }

    function it_implements_ip_resolver()
    {
        $this->shouldImplement(IpResolver::class);
    }

    function it_gets_an_ip(ClientInterface $client, ResponseInterface $response, StreamInterface $stream)
    {
        $client->request('get', CanIHazIpResolver::URI)->willReturn($response);
        $response->getBody()->willReturn($stream);
        $stream->getContents()->willReturn(self::TEST_IP);

        $this->getIpAddress()->__toString()->shouldReturn(self::TEST_IP);
    }
}
