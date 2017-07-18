<?php

namespace Fake;

use DnsUpdater\Record;
use DnsUpdater\Service\RecordPersister\RecordPersister;

class FakeRecordPersister implements RecordPersister
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
     * @return Record[]
     */
    public function getExistingRecords(): array
    {
        return $this->existingRecords;
    }

    /**
     * @param Record[] $existingRecords
     */
    public function setExistingRecords(array $existingRecords)
    {
        $this->existingRecords = $existingRecords;
    }
}
