<?php

declare(strict_types=1);

namespace DnsUpdater\Adapter;

use DigitalOceanV2\Api\DomainRecord as DomainRecordApi;
use DigitalOceanV2\DigitalOceanV2;
use DigitalOceanV2\Entity\DomainRecord;
use DnsUpdater\Value\Record;

final class DigitalOceanAdapter implements Adapter
{
    public const NAME = 'digitalocean';

    /**
     * @var DomainRecord
     */
    private $domainRecords;

    /**
     * @var DomainRecordApi
     */
    private $domainRecordApi;

    public function __construct(DigitalOceanV2 $digitalOceanApi)
    {
        $this->domainRecordApi = $digitalOceanApi->domainRecord();
    }

    public function persist(Record $record): void
    {
        $domainId = $this->fetchDomainId($record->getDomain(), $record->getName());

        if (null !== $domainId) {
            $this->domainRecordApi->updateData($record->getDomain(), $domainId, (string) $record->getValue());

            return;
        }

        $this->domainRecordApi->create(
            $record->getDomain(),
            Record::TYPE_ADDRESS,
            $record->getName(),
            $record->getValue()
        );
    }

    private function fetchDomainId(string $domainName, string $recordName): ?int
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
