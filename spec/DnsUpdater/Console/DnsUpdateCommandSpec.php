<?php

namespace spec\DnsUpdater\Console;

use DnsUpdater\Ip;
use DnsUpdater\Record;
use DnsUpdater\Service\IpResolver\IpResolver;
use DnsUpdater\Service\RecordPersister\RecordPersister;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DnsUpdateCommandSpec extends ObjectBehavior
{
    const TEST_DOMAIN = 'test.domain';
    const TEST_IP = '127.0.0.1';

    function let(
        IpResolver $ipResolver,
        Ip $ip,
        RecordPersister $recordPersister,
        CacheInterface $cache,
        LoggerInterface $logger
    ) {
        $ipResolver->getIp()->willReturn($ip);
        $ip->__toString()->willReturn(self::TEST_IP);

        $this->beConstructedWith(
            $ipResolver,
            $recordPersister,
            $cache,
            $logger,
            [
                self::TEST_DOMAIN => ['a', 'b'],
            ]
        );
    }

    function it_provides_the_dns_update_console_command()
    {
        $this->shouldBeAnInstanceOf(Command::class);
        $this->getName()->shouldReturn('dns:update');
    }

    function it_updates_dns_records_with_current_ip_address(
        RecordPersister $recordPersister,
        LoggerInterface $logger,
        InputInterface $input,
        OutputInterface $output
    ) {
        $recordA = new Record(self::TEST_DOMAIN, 'a', Record::TYPE_ADDRESS, self::TEST_IP);
        $recordPersister->persist($recordA)->willReturn($recordA);
        $recordPersister->persist($recordA)->shouldBeCalled();

        $recordB = new Record(self::TEST_DOMAIN, 'b', Record::TYPE_ADDRESS, self::TEST_IP);
        $recordPersister->persist($recordB)->willReturn($recordB);
        $recordPersister->persist($recordB)->shouldBeCalled();

        $logger->info('Detected a new IP', ['IP' => self::TEST_IP])->shouldBeCalled();
        $logger->info(
            'Updated record',
            [
                'domain' => self::TEST_DOMAIN,
                'host' => 'a',
                'type' => Record::TYPE_ADDRESS,
                'data' => self::TEST_IP,
            ]
        )->shouldBeCalled();
        $logger->info(
            'Updated record',
            [
                'domain' => self::TEST_DOMAIN,
                'host' => 'b',
                'type' => Record::TYPE_ADDRESS,
                'data' => self::TEST_IP,
            ]
        )->shouldBeCalled();

        $this->run($input, $output);
    }

    function it_does_not_updates_dns_records_when_ip_is_already_cached(
        RecordPersister $recordPersister,
        CacheInterface $cache,
        LoggerInterface $logger,
        InputInterface $input,
        OutputInterface $output
    ) {
        $cache->has('ip')->willReturn(true);
        $cache->get('ip')->willReturn(self::TEST_IP);

        $logger->info('IP unchanged', ['IP' => self::TEST_IP])->shouldBeCalled();
        $recordPersister->persist(Argument::any())->shouldNotBeCalled();

        $this->run($input, $output);
    }
}
