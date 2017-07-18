<?php

namespace spec\DnsUpdater;

use DnsUpdater\Record;
use PhpSpec\ObjectBehavior;

class RecordSpec extends ObjectBehavior
{
    const TEST_DOMAIN = 'test.domain';
    const TEST_HOST = 'my';
    const TEST_DATA = '111.222.333.444';

    function let()
    {
        $this->beConstructedWith(self::TEST_DOMAIN, self::TEST_HOST, Record::TYPE_ADDRESS, self::TEST_DATA);
    }

    function it_has_a_domain()
    {
        $this->getDomain()->shouldReturn(self::TEST_DOMAIN);
    }

    function it_has_a_host()
    {
        $this->getHost()->shouldReturn(self::TEST_HOST);
    }

    function it_has_a_type()
    {
        $this->getType()->shouldReturn(Record::TYPE_ADDRESS);
    }

    function it_has_data()
    {
        $this->getData()->shouldReturn(self::TEST_DATA);
    }

    function it_can_overwrite_data_value()
    {
        $this->setData('foo');
        $this->getData()->shouldReturn('foo');
    }

    function it_will_return_same_as_record_with_same_values(Record $record)
    {
        $record->getDomain()->willReturn(self::TEST_DOMAIN);
        $record->getHost()->willReturn(self::TEST_HOST);
        $record->getType()->willReturn(Record::TYPE_ADDRESS);

        $this->shouldBeSame($record);
    }

    function it_will_return_not_same_as_record_with_different_values(Record $record)
    {
        $record->getDomain()->willReturn(self::TEST_DOMAIN);
        $record->getHost()->willReturn(self::TEST_HOST);
        $record->getType()->willReturn('X');

        $this->shouldNotBeSame($record);
    }
}
