<?php

namespace spec\DnsUpdater\Command\Contract;

use DnsUpdater\Record;
use PhpSpec\ObjectBehavior;

class UpdateRecordResponseSpec extends ObjectBehavior
{
    function it_sets_and_gets_record(Record $record)
    {
        $this->setRecord($record);
        $this->getRecord()->shouldReturn($record);
    }
}
