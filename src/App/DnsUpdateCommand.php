<?php

namespace App;

use DigitalOceanV2\Api\DomainRecord as DomainRecordApi;
use DigitalOceanV2\DigitalOceanV2;
use DigitalOceanV2\Entity\DomainRecord;
use IpResolution\Ip;
use IpResolution\Resolver\Resolver;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

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
     * @var array
     */
    private $domains;

    /**
     * @var SymfonyStyle
     */
    private $io;

    /**
     * @param Resolver $ipResolver
     * @param DigitalOceanV2 $digitalOceanApi
     * @param array $domains
     */
    public function __construct(Resolver $ipResolver, DigitalOceanV2 $digitalOceanApi, array $domains)
    {
        $this->ipResolver = $ipResolver;
        $this->domainRecordApi = $digitalOceanApi->domainRecord();
        $this->domains = $domains;

        parent::__construct();
    }

    public function configure()
    {
        $this->setName('dns:update')
            ->setDescription('Updates DNS records');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);

        try {
            // TODO check if IP already in cache, if so don't update
            $ip = $this->ipResolver->getIp();

            foreach ($this->domains as $domainName => $recordNames) {
                $this->upsertDomainRecords($domainName, $recordNames, $ip);
            }

        } catch (\Exception $exception) {
            $this->io->error($exception->getMessage());
        }
    }

    /**
     * @param string $domainName
     * @param string[] $recordNames
     * @param Ip $ip
     */
    private function upsertDomainRecords(string $domainName, array $recordNames, Ip $ip)
    {
        foreach ($this->domainRecordApi->getAll($domainName) as $domainRecord) {
            if (empty($recordNames)) {
                break;
            }

            if (
                self::ADDRESS_RECORD === $domainRecord->type
                && in_array($domainRecord->name, $recordNames)
            ) {
                $updatedRecord = $this->updateDomainRecord($domainName, $domainRecord, $ip);
                $this->io->success("Updated the IP for {$updatedRecord->name}.$domainName to $updatedRecord->data");
                $recordNames = array_diff($recordNames, [$updatedRecord->name]);
            }
        }

        foreach ($recordNames as $recordName) {
            $createdRecord = $this->createDomainRecord($domainName, $recordName, $ip);
            $this->io->success("Added the IP for {$createdRecord->name}.$domainName to $createdRecord->data");
        }
    }

    /**
     * @param string $domainName
     * @param DomainRecord $domainRecord
     * @param Ip $ip
     *
     * @return DomainRecord
     */
    private function updateDomainRecord(string $domainName, DomainRecord $domainRecord, Ip $ip)
    {
        return $this->domainRecordApi->updateData($domainName, $domainRecord->id, (string) $ip);
    }

    /**
     * @param string $domainName
     * @param string $recordName
     * @param Ip $ip
     *
     * @return DomainRecord
     */
    private function createDomainRecord(string $domainName, string $recordName, Ip $ip)
    {
        return $this->domainRecordApi->create($domainName, self::ADDRESS_RECORD, $recordName, (string) $ip);
    }
}
