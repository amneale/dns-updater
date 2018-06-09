<?php

namespace DnsUpdater\Adapter;

use Cloudflare\Zone;
use Cloudflare\Zone\Dns;
use DnsUpdater\Value\Record;

final class CloudFlareAdapter implements Adapter
{
    const NAME = 'cloudflare';
    const STATUS_ACTIVE = 'active';

    /**
     * @var Zone
     */
    private $zone;

    /**
     * @var Dns
     */
    private $dns;

    /**
     * @param Zone $zone
     * @param Dns $dns
     */
    public function __construct(Zone $zone, Dns $dns)
    {
        $this->zone = $zone;
        $this->dns = $dns;
    }

    /**
     * @inheritdoc
     */
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
     * @param \DnsUpdater\Value\Record $record
     *
     * @return string
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

    /**
     * @param string $zoneId
     * @param Record $record
     *
     * @return string|null
     */
    private function fetchRecordId(string $zoneId, Record $record)
    {
        $response = $this->dns->list_records($zoneId, $record->getType(), $this->getNormalisedRecordName($record));
        $this->checkResponse($response);

        return $response->result[0]->id ?? null;
    }

    /**
     * @param Record $record
     *
     * @return string
     */
    private function getNormalisedRecordName(Record $record): string
    {
        return '@' === $record->getName()
            ? $record->getDomain()
            : $record->getName() . '.' . $record->getDomain();
    }

    /**
     * @param \stdClass $response
     */
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
