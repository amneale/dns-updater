<?php

namespace spec\DnsUpdater;

use DnsUpdater\Record;
use PhpSpec\ObjectBehavior;

class RecordSpec extends ObjectBehavior
{
    const TEST_DOMAIN = 'test.domain';
    const TEST_NAME = 'my';
    const TEST_VALUE = '111.222.333.444';

    public function let(): void
    {
        $this->beConstructedWith(self::TEST_DOMAIN, self::TEST_NAME, Record::TYPE_ADDRESS, self::TEST_VALUE);
    }

    public function it_has_a_domain(): void
    {
        $this->getDomain()->shouldReturn(self::TEST_DOMAIN);
    }

    public function it_has_a_name(): void
    {
        $this->getName()->shouldReturn(self::TEST_NAME);
    }

    public function it_has_a_type(): void
    {
        $this->getType()->shouldReturn(Record::TYPE_ADDRESS);
    }

    public function it_has_a_value(): void
    {
        $this->getValue()->shouldReturn(self::TEST_VALUE);
    }

    public function it_will_return_same_as_record_with_same_values(Record $record): void
    {
        $record->getDomain()->willReturn(self::TEST_DOMAIN);
        $record->getName()->willReturn(self::TEST_NAME);
        $record->getType()->willReturn(Record::TYPE_ADDRESS);

        $this->shouldBeSame($record);
    }

    public function it_will_return_not_same_as_record_with_different_values(Record $record): void
    {
        $record->getDomain()->willReturn(self::TEST_DOMAIN);
        $record->getName()->willReturn(self::TEST_NAME);
        $record->getType()->willReturn('X');

        $this->shouldNotBeSame($record);
    }
}
