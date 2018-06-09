<?php

namespace Fake;

use DnsUpdater\Record;
use DnsUpdater\UpdateRecord\UpdateRecord;

class FakeUpdateRecord implements UpdateRecord
{
    /**
     * @var Record[]
     */
    private $existingRecords = [];

    /**
     * @param Record $record
     *
     * @return Record
     */
    public function persist(Record $record): Record
    {
        foreach ($this->existingRecords as $key => $existingRecord) {
            if ($existingRecord->isSame($record)) {
                $this->existingRecords[$key] = $record;

                return $record;
            }
        }

        $this->existingRecords[] = $record;

        return $record;
    }

    /**
     * @param Record[] $existingRecords
     */
    public function setExistingRecords(array $existingRecords): void
    {
        $this->existingRecords = $existingRecords;
    }

    /**
     * @return Record[]
     */
    public function getExistingRecords(): array
    {
        return $this->existingRecords;
    }

    /**
     * @param Record $record
     *
     * @return Record|null
     */
    public function find(Record $record): ?Record
    {
        foreach ($this->existingRecords as $existingRecord) {
            if ($existingRecord->isSame($record)) {
                return $existingRecord;
            }
        }

        return null;
    }
}
