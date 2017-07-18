<?php

namespace DnsUpdater\Service\RecordPersister;

use DigitalOceanV2\Api\DomainRecord as DomainRecordApi;
use DigitalOceanV2\DigitalOceanV2;
use DigitalOceanV2\Entity\DomainRecord;
use DnsUpdater\Record;

final class DigitalOceanAdapter implements RecordPersister
{
    /**
     * @var DomainRecord
     */
    private $domainRecords;

    /**
     * @var DomainRecordApi
     */
    private $domainRecordApi;

    /**
     * @param DigitalOceanV2 $digitalOceanApi
     */
    public function __construct(DigitalOceanV2 $digitalOceanApi)
    {
        $this->domainRecordApi = $digitalOceanApi->domainRecord();
    }

    /**
     * @param \DnsUpdater\Record $record
     *
     * @return \DnsUpdater\Record
     */
    public function persist(Record $record): Record
    {
        $id = $this->fetchDomainId($record->getDomain(), $record->getHost());

        null !== $id
            ? $this->domainRecordApi->updateData($record->getDomain(), $id, (string) $record->getData())
            : $this->domainRecordApi->create(
                $record->getDomain(), Record::TYPE_ADDRESS, $record->getHost(), $record->getData()
            );

        return $record;
    }

    /**
     * @param string $domainName
     * @param string $recordName
     *
     * @return int|null
     */
    private function fetchDomainId(string $domainName, string $recordName)
    {
        if (!isset($this->domainRecords[$domainName])) {
            $this->domainRecords[$domainName] = $this->domainRecordApi->getAll($domainName);
        }

        foreach ($this->domainRecords[$domainName] as $domainRecord) {
            if ($recordName === $domainRecord->name && Record::TYPE_ADDRESS === $domainRecord->type) {
                return $domainRecord->id;
            }
        }

        return null;
    }
}
