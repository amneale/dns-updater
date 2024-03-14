<?php

declare(strict_types=1);

namespace DnsUpdater\Adapter;

use DigitalOceanV2\Api\DomainRecord as DomainRecordApi;
use DigitalOceanV2\Client;
use DigitalOceanV2\Entity\DomainRecord;
use DnsUpdater\Value\Record;

final class DigitalOceanAdapter implements Adapter
{
    public const NAME = 'digitalocean';

    /** @var DomainRecord[] */
    private array $domainRecords;

    private DomainRecordApi $domainRecordApi;

    public function __construct(Client $digitalOceanApi)
    {
        $this->domainRecordApi = $digitalOceanApi->domainRecord();
    }

    public function persist(Record $record): void
    {
        $domainId = $this->fetchDomainId($record->getDomain(), $record->getName());

        if (null !== $domainId) {
            $this->domainRecordApi->updateData($record->getDomain(), $domainId, $record->getValue());

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
