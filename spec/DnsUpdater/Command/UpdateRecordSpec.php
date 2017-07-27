<?php

namespace spec\DnsUpdater\Command;

use DnsUpdater\Command\Contract\UpdateRecordRequest;
use DnsUpdater\Command\Contract\UpdateRecordResponse;
use DnsUpdater\Command\Repository\UpdateRecordRepository;
use DnsUpdater\Command\Service\IpResolver;
use DnsUpdater\Ip;
use DnsUpdater\Record;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;

class UpdateRecordSpec extends ObjectBehavior
{
    const TEST_IP = '123.45.67.89';
    const TEST_DOMAIN = 'domain.name';
    const TEST_HOST = 'test';

    function let(
        IpResolver $ipResolver,
        UpdateRecordRepository $recordRepository,
        CacheInterface $cache,
        LoggerInterface $logger,
        Record $record,
        UpdateRecordRequest $request
    ) {
        $ipResolver->getIp()->willReturn(new Ip(self::TEST_IP));
        $recordRepository->persist($record)->willReturn($record);
        $cache->has('ip_' . self::TEST_HOST . '_' . self::TEST_DOMAIN)->willReturn(true);
        $cache->get('ip_' . self::TEST_HOST . '_' . self::TEST_DOMAIN)->willReturn(self::TEST_IP);

        $request->getRecord()->willReturn($record);

        $record->getDomain()->willReturn(self::TEST_DOMAIN);
        $record->getHost()->willReturn(self::TEST_HOST);
        $record->getType()->willReturn(Record::TYPE_ADDRESS);
        $record->getData()->willReturn(self::TEST_IP);

        $this->beConstructedWith($ipResolver, $recordRepository, $cache, $logger);
    }

    function it_updates_a_record(
        UpdateRecordRequest $request,
        UpdateRecordResponse $response,
        Record $record,
        CacheInterface $cache,
        LoggerInterface $logger
    ) {
        $cache->get('ip_' . self::TEST_HOST . '_' . self::TEST_DOMAIN)->willReturn('111.222.333.444');
        $record->getData()->willReturn('111.222.333.444');

        $record->setData(self::TEST_IP)->shouldBeCalled();
        $cache->set('ip_' . self::TEST_HOST . '_' . self::TEST_DOMAIN, self::TEST_IP)->shouldBeCalled();
        $logger->info(
            'Updated record',
            [
                'domain' => self::TEST_DOMAIN,
                'host' => self::TEST_HOST,
                'type' => Record::TYPE_ADDRESS,
                'data' => '111.222.333.444',
            ]
        )->shouldBeCalled();
        $response->setRecord($record)->shouldBeCalled();

        $this->handle($request, $response);
    }

    function it_does_not_updates_a_record_when_ip_is_already_cached(
        UpdateRecordRequest $request,
        UpdateRecordResponse $response,
        Record $record,
        CacheInterface $cache,
        LoggerInterface $logger
    ) {
        $cache->get('ip_' . self::TEST_HOST . '_' . self::TEST_DOMAIN)->willReturn(self::TEST_IP);

        $logger->info(
            'IP unchanged',
            [
                'domain' => self::TEST_DOMAIN,
                'host' => self::TEST_HOST,
                'type' => Record::TYPE_ADDRESS,
                'data' => self::TEST_IP,
            ]
        )->shouldBeCalled();
        $record->setData(self::TEST_IP)->shouldNotBeCalled();
        $response->setRecord($record)->shouldNotBeCalled();

        $this->handle($request, $response);
    }

    function it_logs_exception_when_operation_fails(
        UpdateRecordRequest $request,
        UpdateRecordResponse $response,
        IpResolver $ipResolver,
        LoggerInterface $logger
    ) {
        $ipResolver->getIp()->willThrow(new \Exception('test exception'));

        $logger->error('test exception')->shouldBeCalled();

        $this->handle($request, $response);
    }
}
