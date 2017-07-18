<?php

namespace spec\DnsUpdater\Console;

use DnsUpdater\Service\IpResolver\IpResolver;
use DnsUpdater\Service\RecordPersister\RecordPersister;
use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;

class DnsUpdateCommandSpec extends ObjectBehavior
{
    function let(IpResolver $ipResolver, RecordPersister $recordPersister, LoggerInterface $logger)
    {
        $this->beConstructedWith(
            $ipResolver,
            $recordPersister,
            $logger,
            []
        );
    }

    function it_provides_the_dns_update_console_command()
    {
        $this->shouldBeAnInstanceOf(Command::class);
        $this->getName()->shouldReturn('dns:update');
    }
}
