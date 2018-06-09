<?php

namespace spec\DnsUpdater\UpdateRecord;

use DigitalOceanV2\Api\DomainRecord as DomainRecordApi;
use DigitalOceanV2\DigitalOceanV2;
use DigitalOceanV2\Entity\DomainRecord;
use DnsUpdater\Record;
use DnsUpdater\UpdateRecord\UpdateRecord;
use PhpSpec\ObjectBehavior;

class DigitalOceanAdapterSpec extends ObjectBehavior
{
    const TEST_DOMAIN = 'test.domain';
    const TEST_HOST = '@';
    const TEST_DATA = '123.45.67.89';

    public function let(DigitalOceanV2 $digitalOceanApi, DomainRecordApi $domainRecordApi, Record $record): void
    {
        $digitalOceanApi->domainRecord()->willReturn($domainRecordApi);

        $record->getDomain()->willReturn(self::TEST_DOMAIN);
        $record->getName()->willReturn(self::TEST_HOST);
        $record->getValue()->willReturn(self::TEST_DATA);

        $this->beConstructedWith($digitalOceanApi);
    }

    public function it_implements_record_persister_adapter(): void
    {
        $this->shouldImplement(UpdateRecord::class);
    }

    public function it_creates_a_new_record(DomainRecordApi $domainRecordApi, Record $record): void
    {
        $domainRecordApi->getAll(self::TEST_DOMAIN)->willReturn([]);
        $domainRecordApi->create(self::TEST_DOMAIN, Record::TYPE_ADDRESS, self::TEST_HOST, self::TEST_DATA)
            ->shouldBeCalled();

        $this->persist($record)->shouldReturn($record);
    }

    public function it_updates_an_existing_record(DomainRecordApi $domainRecordApi, Record $record): void
    {
        $testId = 123;
        $domainRecordApi->getAll(self::TEST_DOMAIN)->willReturn([
            new DomainRecord([
                'id' => $testId,
                'type' => Record::TYPE_ADDRESS,
                'name' => self::TEST_HOST,
            ])
        ]);
        $domainRecordApi->updateData(self::TEST_DOMAIN, $testId, self::TEST_DATA)->shouldBeCalled();

        $this->persist($record)->shouldReturn($record);
    }
}
