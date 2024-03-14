<?php

declare(strict_types=1);

namespace DnsUpdater\Adapter;

use Cloudflare\Zone;
use Cloudflare\Zone\Dns;
use DnsUpdater\Value\Record;

final class CloudFlareAdapter implements Adapter
{
    public const NAME = 'cloudflare';
    public const STATUS_ACTIVE = 'active';

    public function __construct(private readonly Zone $zone, private readonly Dns $dns) {}

    public function persist(Record $record): void
    {
        $zoneId = $this->getZoneId($record);
        $recordId = $this->fetchRecordId($zoneId, $record);

        $response = null !== $recordId
            ? $this->dns->update($zoneId, $recordId, $record->getType(), $record->getName(), $record->getValue())
            : $this->dns->create($zoneId, $record->getType(), $record->getName(), $record->getValue());

        $this->checkResponse($response);
    }

    /**
     * @throws \Exception
     */
    private function getZoneId(Record $record): string
    {
        $response = $this->zone->zones($record->getDomain(), self::STATUS_ACTIVE);
        $this->checkResponse($response);

        if (!isset($response->result[0]->id)) {
            throw new \InvalidArgumentException("Zone for domain {$record->getDomain()} not found");
        }

        return $response->result[0]->id;
    }

    private function fetchRecordId(string $zoneId, Record $record): ?string
    {
        $response = $this->dns->list_records($zoneId, $record->getType(), $this->getNormalisedRecordName($record));
        $this->checkResponse($response);

        return $response->result[0]->id ?? null;
    }

    private function getNormalisedRecordName(Record $record): string
    {
        return '@' === $record->getName()
            ? $record->getDomain()
            : $record->getName().'.'.$record->getDomain();
    }

    private function checkResponse(\stdClass $response): void
    {
        if (!$response->success) {
            $error = '' !== $response->error
                ? $response->error
                : ($response->errors[0]->message ?? 'Unexpected runtime error');

            throw new \RuntimeException($error);
        }
    }
}
