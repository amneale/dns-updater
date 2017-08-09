<?php

namespace spec\DnsUpdater\Repository;

use Cloudflare\Zone;
use Cloudflare\Zone\Dns;
use DnsUpdater\Command\Repository\UpdateRecordRepository;
use DnsUpdater\Record;
use DnsUpdater\Repository\CloudFlareAdapter;
use PhpSpec\ObjectBehavior;

class CloudFlareAdapterSpec extends ObjectBehavior
{
    const ZONE_ID = '1234567890';
    const RECORD_ID = '0987654321';
    const HOST = 'my';
    const DOMAIN = 'test.domain';
    const IP = '123.456.789.0';

    function let(Zone $zone, Dns $dns, Record $record)
    {
        $zone->zones(self::DOMAIN, CloudFlareAdapter::STATUS_ACTIVE)->willReturn(
            (object) [
                'success' => true,
                'result' => [
                    (object) ['id' => self::ZONE_ID]
                ],
            ]
        );

        $dns->list_records(self::ZONE_ID, Record::TYPE_ADDRESS, self::HOST . '.' . self::DOMAIN)->willReturn(
            (object) [
                'success' => true,
                'result' => [
                    (object) ['id' => self::RECORD_ID]
                ]
            ]
        );

        $record->getDomain()->willReturn(self::DOMAIN);
        $record->getHost()->willReturn(self::HOST);
        $record->getType()->willReturn(Record::TYPE_ADDRESS);
        $record->getData()->willReturn(self::IP);

        $this->beConstructedWith($zone, $dns);
    }

    function it_implements_record_persister_adapter()
    {
        $this->shouldImplement(UpdateRecordRepository::class);
    }

    function it_creates_a_new_record(Dns $dns, Record $record)
    {
        $dns->list_records(self::ZONE_ID, Record::TYPE_ADDRESS, self::HOST . '.' . self::DOMAIN)->willReturn(
            (object) ['success' => true]
        );
        $dns->create(self::ZONE_ID, Record::TYPE_ADDRESS, self::HOST, self::IP)->willReturn(
            (object) ['success' => true]
        );

        $this->persist($record)->shouldReturn($record);
    }

    function it_updates_an_existing_record(Dns $dns, Record $record)
    {
        $dns->list_records(self::ZONE_ID, Record::TYPE_ADDRESS, self::HOST . '.' . self::DOMAIN)->willReturn(
            (object) [
                'success' => true,
                'result' => [
                    (object) ['id' => self::RECORD_ID]
                ]
            ]
        );

        $dns->update(self::ZONE_ID, self::RECORD_ID, Record::TYPE_ADDRESS, self::HOST, self::IP)->willReturn(
            (object) ['success' => true]
        );

        $this->persist($record)->shouldReturn($record);
    }

    function it_throws_an_exception_if_it_does_not_find_a_zone_id(Zone $zone, Record $record)
    {
        $zone->zones(self::DOMAIN, CloudFlareAdapter::STATUS_ACTIVE)->willReturn((object) ['success' => true]);

        $this->shouldThrow(\InvalidArgumentException::class)->during('persist', [$record]);
    }

    function it_throws_an_exception_if_the_zones_endpoint_errors(Zone $zone, Record $record)
    {
        $zone->zones(self::DOMAIN, CloudFlareAdapter::STATUS_ACTIVE)->willReturn(
            (object) [
                'success' => false,
                'error' => 'zones_error',
            ]
        );

        $this->shouldThrow(new \RuntimeException('zones_error'))->during('persist', [$record]);
    }

    function it_throws_an_exception_if_the_list_records_endpoint_errors(Dns $dns, Record $record)
    {
        $dns->list_records(self::ZONE_ID, Record::TYPE_ADDRESS, self::HOST . '.' . self::DOMAIN)->willReturn(
            (object) [
                'success' => false,
                'error' => 'list_records_error',
            ]
        );

        $this->shouldThrow(new \RuntimeException('list_records_error'))->during('persist', [$record]);
    }

    function it_throws_an_exception_if_the_create_record_endpoint_errors(Dns $dns, Record $record)
    {
        $dns->list_records(self::ZONE_ID, Record::TYPE_ADDRESS, self::HOST . '.' . self::DOMAIN)->willReturn(
            (object) ['success' => true]
        );
        $dns->create(self::ZONE_ID, Record::TYPE_ADDRESS, self::HOST, self::IP)->willReturn(
            (object) [
                'success' => false,
                'error' => 'create_record_error',
            ]
        );

        $this->shouldThrow(new \RuntimeException('create_record_error'))->during('persist', [$record]);
    }

    function it_throws_an_exception_if_the_update_record_endpoint_errors(Dns $dns, Record $record)
    {
        $dns->update(self::ZONE_ID, self::RECORD_ID, Record::TYPE_ADDRESS, self::HOST, self::IP)->willReturn(
            (object) [
                'success' => false,
                'error' => 'update_record_error',
            ]
        );

        $this->shouldThrow(new \RuntimeException('update_record_error'))->during('persist', [$record]);
    }

    function it_translates_root_domain_name(Record $record, Dns $dns)
    {
        $record->getHost()->willReturn('@');
        $dns->list_records(self::ZONE_ID, Record::TYPE_ADDRESS, self::DOMAIN)->willReturn((object) ['success' => true]);
        $dns->create(self::ZONE_ID, Record::TYPE_ADDRESS, '@', self::IP)->willReturn((object) ['success' => true]);

        $this->persist($record)->shouldReturn($record);
    }
}
