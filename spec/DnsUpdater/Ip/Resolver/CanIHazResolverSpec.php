<?php

namespace spec\DnsUpdater\Ip\Resolver;

use GuzzleHttp\ClientInterface;
use DnsUpdater\Ip\Resolver\CanIHazResolver;
use DnsUpdater\Ip\Resolver\Resolver;
use PhpSpec\ObjectBehavior;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class CanIHazResolverSpec extends ObjectBehavior
{
    const TEST_IP = '192.168.0.1';

    function let(ClientInterface $client)
    {
        $this->beConstructedWith($client);
    }

    function it_implements_ip_resolver()
    {
        $this->shouldImplement(Resolver::class);
    }

    function it_gets_an_ip(ClientInterface $client, ResponseInterface $response, StreamInterface $stream)
    {
        $client->request('get', CanIHazResolver::URI)->willReturn($response);
        $response->getBody()->willReturn($stream);
        $stream->getContents()->willReturn(self::TEST_IP);

        $this->getIp()->__toString()->shouldReturn(self::TEST_IP);
    }
}
