<?php

namespace spec\DnsUpdater\Command\Contract;

use DnsUpdater\Record;
use PhpSpec\ObjectBehavior;

class UpdateRecordRequestSpec extends ObjectBehavior
{
    function let(Record $record)
    {
        $this->beConstructedWith($record);
    }

    function it_has_a_record(Record $record)
    {
        $this->getRecord()->shouldReturn($record);
    }
}
