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
     * @param LoggerInterface $logger
     * @param string[] $domains
     */
    public function __construct(
        IpResolver $ipResolver,
        RecordPersister $recordPersister,
        LoggerInterface $logger,
        array $domains
    ) {
        $this->ipResolver = $ipResolver;
        $this->recordPersister = $recordPersister;
        $this->logger = $logger;
        $this->domains = $domains;

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
            // TODO check if IP already in cache, if so don't update
            $ip = $this->ipResolver->getIp();

            $this->logger->info('Detected a new IP', ['IP' => (string) $ip]);
            foreach ($this->domains as $domain => $hosts) {
                foreach ($hosts as $host) {
                    $persistedRecord = $this->recordPersister->persist(
                        new Record($domain, $host, Record::TYPE_ADDRESS, (string) $ip)
                    );
                    $this->logSuccess($persistedRecord);
                }
            }
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
        }
    }

    /**
     * @param Record $record
     */
    private function logSuccess(Record $record)
    {
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
