<?php

namespace spec\DnsUpdater\Console;

use DnsUpdater\Command\Contract\UpdateRecordRequest;
use DnsUpdater\Command\Contract\UpdateRecordResponse;
use DnsUpdater\Command\UpdateRecord;
use DnsUpdater\Record;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DnsUpdateCommandSpec extends ObjectBehavior
{
    const TEST_DOMAIN = 'domain.name';

    function let(UpdateRecord $updateRecord)
    {
        $this->beConstructedWith($updateRecord, self::TEST_DOMAIN, ['@', 'test']);
    }

    function it_provides_the_dns_update_console_command()
    {
        $this->shouldBeAnInstanceOf(Command::class);
        $this->getName()->shouldReturn('dns:update');
    }

    function it_updates_each_host(
        UpdateRecord $updateRecord,
        InputInterface $input,
        OutputInterface $output
    ) {
        $updateRecord->handle(
            new UpdateRecordRequest(new Record(self::TEST_DOMAIN, '@', Record::TYPE_ADDRESS)),
            Argument::type(UpdateRecordResponse::class)
        )->shouldBeCalled();

        $updateRecord->handle(
            new UpdateRecordRequest(new Record(self::TEST_DOMAIN, 'test', Record::TYPE_ADDRESS)),
            Argument::type(UpdateRecordResponse::class)
        )->shouldBeCalled();

        $this->run($input, $output);
    }
}
