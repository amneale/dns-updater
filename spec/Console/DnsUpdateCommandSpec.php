<?php

declare(strict_types=1);

namespace spec\DnsUpdater\Console;

use DnsUpdater\Adapter\AdapterFactory;
use DnsUpdater\IpResolver\IpResolver;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Console\Command\Command;

class DnsUpdateCommandSpec extends ObjectBehavior
{
    public function let(IpResolver $ipResolver, AdapterFactory $adapterFactory): void
    {
        $this->beConstructedWith($ipResolver, $adapterFactory);
    }

    public function it_provides_the_dns_update_console_command(): void
    {
        $this->shouldBeAnInstanceOf(Command::class);
        $this->getName()->shouldReturn('dns:update');
    }
}
