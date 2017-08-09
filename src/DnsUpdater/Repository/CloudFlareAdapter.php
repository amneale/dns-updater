<?php

namespace DnsUpdater\Repository;

use Cloudflare\Zone;
use Cloudflare\Zone\Dns;
use DnsUpdater\Command\Repository\UpdateRecordRepository;
use DnsUpdater\Record;

final class CloudFlareAdapter implements UpdateRecordRepository
{
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
     * @param Record $record
     *
     * @return Record
     */
    public function persist(Record $record): Record
    {
        $zoneId = $this->getZoneId($record);
        $recordId = $this->fetchRecordId($zoneId, $record);

        $response = $recordId !== null
            ? $this->dns->update($zoneId, $recordId, $record->getType(), $record->getHost(), $record->getData())
            : $this->dns->create($zoneId, $record->getType(), $record->getHost(), $record->getData());

        $this->checkResponse($response);

        return $record;
    }

    /**
     * @param Record $record
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
        return $record->getHost() === '@'
            ? $record->getDomain()
            : $record->getHost() . '.' . $record->getDomain();
    }

    /**
     * @param \stdClass $response
     */
    private function checkResponse(\stdClass $response)
    {
        if (!$response->success) {
            $error = $response->error !== ''
                ? $response->error
                : ($response->errors[0]->message ?? 'Unexpected runtime error');

            throw new \RuntimeException($error);
        }
    }
}
