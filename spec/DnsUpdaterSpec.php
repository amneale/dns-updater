<?php

declare(strict_types=1);

namespace spec\DnsUpdater;

use DnsUpdater\Adapter\Adapter;
use DnsUpdater\Value\Record;
use PhpSpec\ObjectBehavior;

class DnsUpdaterSpec extends ObjectBehavior
{
    public function let(Adapter $adapter): void
    {
        $this->beConstructedWith($adapter);
    }

    public function it_persists_records(Record $record, Adapter $adapter): void
    {
        $adapter->persist($record)->shouldBeCalled();

        $this->persist($record);
    }
}
