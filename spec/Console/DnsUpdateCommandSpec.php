<?php

namespace spec\DnsUpdater\Console;

use PhpSpec\ObjectBehavior;
use Symfony\Component\Console\Command\Command;

class DnsUpdateCommandSpec extends ObjectBehavior
{
    public function it_provides_the_dns_update_console_command(): void
    {
        $this->shouldBeAnInstanceOf(Command::class);
        $this->getName()->shouldReturn('dns:update');
    }
}
