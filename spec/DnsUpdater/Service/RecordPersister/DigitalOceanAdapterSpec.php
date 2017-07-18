<?php

namespace spec\DnsUpdater\Service\RecordPersister;

use DigitalOceanV2\Api\DomainRecord as DomainRecordApi;
use DigitalOceanV2\DigitalOceanV2;
use DigitalOceanV2\Entity\DomainRecord;
use DnsUpdater\Record;
use DnsUpdater\Service\RecordPersister\RecordPersister;
use PhpSpec\ObjectBehavior;

class DigitalOceanAdapterSpec extends ObjectBehavior
{
    const TEST_DOMAIN = 'test.domain';
    const TEST_HOST = '@';
    const TEST_DATA = '123.45.67.89';

    function let(DigitalOceanV2 $digitalOceanApi, DomainRecordApi $domainRecordApi, Record $record)
    {
        $digitalOceanApi->domainRecord()->willReturn($domainRecordApi);

        $record->getDomain()->willReturn(self::TEST_DOMAIN);
        $record->getHost()->willReturn(self::TEST_HOST);
        $record->getData()->willReturn(self::TEST_DATA);

        $this->beConstructedWith($digitalOceanApi);
    }

    function it_implements_record_persister_adapter()
    {
        $this->shouldImplement(RecordPersister::class);
    }

    function it_creates_a_new_record(DomainRecordApi $domainRecordApi, Record $record)
    {
        $domainRecordApi->getAll(self::TEST_DOMAIN)->willReturn([]);
        $domainRecordApi->create(self::TEST_DOMAIN, Record::TYPE_ADDRESS, self::TEST_HOST, self::TEST_DATA)
            ->shouldBeCalled();

        $this->persist($record)->shouldReturn($record);
    }

    function it_updates_an_existing_record(DomainRecordApi $domainRecordApi, Record $record)
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