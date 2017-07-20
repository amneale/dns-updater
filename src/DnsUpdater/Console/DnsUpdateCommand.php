<?php

namespace DnsUpdater\Console;

use DigitalOceanV2\Api\DomainRecord as DomainRecordApi;
use DigitalOceanV2\DigitalOceanV2;
use DigitalOceanV2\Entity\DomainRecord;
use DnsUpdater\Ip;
use DnsUpdater\Record;
use DnsUpdater\Service\IpResolver\IpResolver;
use DnsUpdater\Service\RecordPersister\RecordPersister;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DnsUpdateCommand extends Command
{
    /**
     * @var IpResolver
     */
    private $ipResolver;

    /**
     * @var RecordPersister
     */
    private $recordPersister;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var string[]
     */
    private $domains;

    /**
     * @param IpResolver $ipResolver
     * @param RecordPersister $recordPersister
     * @param CacheInterface $cache
     * @param LoggerInterface $logger
     * @param string[] $domains
     */
    public function __construct(
        IpResolver $ipResolver,
        RecordPersister $recordPersister,
        CacheInterface $cache,
        LoggerInterface $logger,
        array $domains
    ) {
        $this->ipResolver = $ipResolver;
        $this->recordPersister = $recordPersister;
        $this->logger = $logger;
        $this->domains = $domains;
        $this->cache = $cache;

        parent::__construct();
    }

    /**
     * @inheritdoc
     */
    public function configure()
    {
        $this->setName('dns:update')->setDescription('Updates DNS records');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $ip = $this->ipResolver->getIp();
            if ($this->shouldUpdateDnsRecords($ip)) {
                $this->updateDnsRecords($ip);
                $this->cache->set('ip', (string) $ip);
            }
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
        }
    }

    /**
     * @param Ip $ip
     *
     * @return bool
     */
    private function shouldUpdateDnsRecords(Ip $ip): bool
    {
        if ($this->cache->has('ip') && $this->cache->get('ip') === (string) $ip) {
            $this->logger->info('IP unchanged', ['IP' => (string) $ip]);

            return false;
        }

        $this->logger->info('Detected a new IP', ['IP' => (string) $ip]);

        return true;
    }

    /**
     * @param Ip $ip
     */
    private function updateDnsRecords(Ip $ip)
    {
        foreach ($this->domains as $domain => $hosts) {
            foreach ($hosts as $host) {
                $record = $this->recordPersister->persist(
                    new Record($domain, $host, Record::TYPE_ADDRESS, (string) $ip)
                );

                $this->logger->info(
                    'Updated record',
                    [
                        'domain' => $record->getDomain(),
                        'host' => $record->getHost(),
                        'type' => $record->getType(),
                        'data' => $record->getData(),
                    ]
                );
            }
        }
    }
}
