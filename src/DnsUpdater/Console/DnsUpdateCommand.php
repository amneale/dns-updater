<?php

namespace DnsUpdater\Console;

use DigitalOceanV2\Api\DomainRecord as DomainRecordApi;
use DigitalOceanV2\DigitalOceanV2;
use DigitalOceanV2\Entity\DomainRecord;
use DnsUpdater\Ip\Ip;
use DnsUpdater\Ip\Resolver\Resolver;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DnsUpdateCommand extends Command
{
    const ADDRESS_RECORD = 'A';

    /**
     * @var Resolver
     */
    private $ipResolver;

    /**
     * @var DomainRecordApi
     */
    private $domainRecordApi;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var string[]
     */
    private $domains;

    /**
     * @var DomainRecord[]
     */
    private $domainRecords;

    /**
     * @param Resolver $ipResolver
     * @param DigitalOceanV2 $digitalOceanApi
     * @param LoggerInterface $logger
     * @param string[] $domains
     */
    public function __construct(
        Resolver $ipResolver,
        DigitalOceanV2 $digitalOceanApi,
        LoggerInterface $logger,
        array $domains
    ) {
        $this->ipResolver = $ipResolver;
        $this->domainRecordApi = $digitalOceanApi->domainRecord();
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
            foreach ($this->domains as $domainName => $recordNames) {
                foreach ($recordNames as $recordName) {
                    $this->upsertDomainRecord($domainName, $recordName, $ip);
                }
            }
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
        }
    }

    /**
     * @param string $domainName
     * @param string $recordName
     * @param Ip $ip
     */
    private function upsertDomainRecord(string $domainName, string $recordName, Ip $ip)
    {
        $domainRecord = $this->fetchDomainRecord($domainName, $recordName);
        $upsertedRecord = $domainRecord
            ? $this->domainRecordApi->updateData($domainName, $domainRecord->id, (string) $ip)
            : $this->domainRecordApi->create($domainName, self::ADDRESS_RECORD, $recordName, (string) $ip);

        $this->logger->info(
            ($domainRecord ? 'Updated' : 'Inserted') . ' record',
            ['record' => $upsertedRecord]
        );
    }

    /**
     * @param string $domainName
     * @param string $recordName
     *
     * @return DomainRecord|null
     */
    private function fetchDomainRecord(string $domainName, string $recordName)
    {
        if (!isset($this->domainRecords[$domainName])) {
            $this->domainRecords[$domainName] = $this->domainRecordApi->getAll($domainName);
        }

        foreach ($this->domainRecords[$domainName] as $domainRecord) {
            if ($recordName === $domainRecord->name && self::ADDRESS_RECORD === $domainRecord->type) {
                return $domainRecord;
            }
        }

        return null;
    }
}
