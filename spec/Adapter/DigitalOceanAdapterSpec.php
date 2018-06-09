<?php

namespace spec\DnsUpdater\Adapter;

use DigitalOceanV2\Api\DomainRecord as DomainRecordApi;
use DigitalOceanV2\DigitalOceanV2;
use DigitalOceanV2\Entity\DomainRecord;
use DnsUpdater\Adapter\Adapter;
use DnsUpdater\Value\Record;
use PhpSpec\ObjectBehavior;

class DigitalOceanAdapterSpec extends ObjectBehavior
{
    const TEST_DOMAIN = 'test.domain';
    const TEST_HOST = '@';
    const TEST_DATA = '123.45.67.89';
    const TEST_ID = 123;

    public function let(DigitalOceanV2 $digitalOceanApi, DomainRecordApi $domainRecordApi, Record $record): void
    {
        $digitalOceanApi->domainRecord()->willReturn($domainRecordApi);

        $record->getDomain()->willReturn(self::TEST_DOMAIN);
        $record->getName()->willReturn(self::TEST_HOST);
        $record->getValue()->willReturn(self::TEST_DATA);

        $this->beConstructedWith($digitalOceanApi);
    }

    public function it_implements_adapter(): void
    {
        $this->shouldImplement(Adapter::class);
    }

    public function it_creates_a_new_record(DomainRecordApi $domainRecordApi, Record $record): void
    {
        $domainRecordApi->getAll(self::TEST_DOMAIN)->willReturn([]);
        $domainRecordApi->create(self::TEST_DOMAIN, Record::TYPE_ADDRESS, self::TEST_HOST, self::TEST_DATA)
            ->shouldBeCalled();

        $this->persist($record);
    }

    public function it_updates_an_existing_record(DomainRecordApi $domainRecordApi, Record $record): void
    {
        $domainRecordApi->getAll(self::TEST_DOMAIN)->willReturn([
            new DomainRecord([
                'id' => self::TEST_ID,
                'type' => Record::TYPE_ADDRESS,
                'name' => self::TEST_HOST,
            ])
        ]);
        $domainRecordApi->updateData(self::TEST_DOMAIN, self::TEST_ID, self::TEST_DATA)->shouldBeCalled();

        $this->persist($record);
    }
}
