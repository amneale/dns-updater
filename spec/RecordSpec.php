<?php

namespace spec\DnsUpdater;

use DnsUpdater\Record;
use PhpSpec\ObjectBehavior;

class RecordSpec extends ObjectBehavior
{
    const TEST_DOMAIN = 'test.domain';
    const TEST_NAME = 'my';
    const TEST_VALUE = '111.222.333.444';

    function let()
    {
        $this->beConstructedWith(self::TEST_DOMAIN, self::TEST_NAME, Record::TYPE_ADDRESS, self::TEST_VALUE);
    }

    function it_has_a_domain()
    {
        $this->getDomain()->shouldReturn(self::TEST_DOMAIN);
    }

    function it_has_a_name()
    {
        $this->getName()->shouldReturn(self::TEST_NAME);
    }

    function it_has_a_type()
    {
        $this->getType()->shouldReturn(Record::TYPE_ADDRESS);
    }

    function it_has_a_value()
    {
        $this->getValue()->shouldReturn(self::TEST_VALUE);
    }

    function it_will_return_same_as_record_with_same_values(Record $record)
    {
        $record->getDomain()->willReturn(self::TEST_DOMAIN);
        $record->getName()->willReturn(self::TEST_NAME);
        $record->getType()->willReturn(Record::TYPE_ADDRESS);

        $this->shouldBeSame($record);
    }

    function it_will_return_not_same_as_record_with_different_values(Record $record)
    {
        $record->getDomain()->willReturn(self::TEST_DOMAIN);
        $record->getName()->willReturn(self::TEST_NAME);
        $record->getType()->willReturn('X');

        $this->shouldNotBeSame($record);
    }
}
