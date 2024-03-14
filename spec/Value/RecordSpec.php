<?php

declare(strict_types=1);

namespace spec\DnsUpdater\Value;

use DnsUpdater\Value\Record;
use PhpSpec\ObjectBehavior;

class RecordSpec extends ObjectBehavior
{
    public const TEST_DOMAIN = 'test.domain';
    public const TEST_NAME = 'my';
    public const TEST_VALUE = '111.222.333.444';

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
}
