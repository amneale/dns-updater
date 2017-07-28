<?php

namespace spec\DnsUpdater;

use PhpSpec\ObjectBehavior;

class IpAddressSpec extends ObjectBehavior
{
    const TEST_IP = '192.168.0.1';

    function let()
    {
        $this->beConstructedWith(self::TEST_IP);
    }

    function it_can_be_represented_as_a_string()
    {
        $this->__toString()->shouldReturn(self::TEST_IP);
    }

    function it_can_be_constructed_with_an_untrimmed_string()
    {
        $this->beConstructedWith(self::TEST_IP . PHP_EOL);
        $this->__toString()->shouldReturn(self::TEST_IP);
    }

    function it_throws_an_exception_when_constructed_with_invalid_format()
    {
        $this->beConstructedWith('foobar');
        $this->shouldThrow(\InvalidArgumentException::class)->duringInstantiation();
    }
}
