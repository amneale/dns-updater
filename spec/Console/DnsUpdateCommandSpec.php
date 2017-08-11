<?php

namespace spec\DnsUpdater\Console;

use PhpSpec\ObjectBehavior;
use Symfony\Component\Console\Command\Command;

class DnsUpdateCommandSpec extends ObjectBehavior
{
    function it_provides_the_dns_update_console_command()
    {
        $this->shouldBeAnInstanceOf(Command::class);
        $this->getName()->shouldReturn('dns:update');
    }
}
