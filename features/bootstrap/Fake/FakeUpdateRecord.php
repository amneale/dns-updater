<?php

namespace Fake;

use DnsUpdater\Record;
use DnsUpdater\UpdateRecord\UpdateRecord;

class FakeUpdateRecord implements UpdateRecord
{
    /**
     * @var Record[]
     */
    public $existingRecords = [];

    /**
     * @inheritdoc
     */
    public function persist(Record $record): Record
    {
        foreach ($this->existingRecords as $key => $existingRecord) {
            if (
                $existingRecord->getDomain() === $record->getDomain() &&
                $existingRecord->getName() === $record->getName() &&
                $existingRecord->getType() === $record->getType()
            ) {
                $this->existingRecords[$key] = $record;

                return $record;
            }
        }

        $this->existingRecords[] = $record;

        return $record;
    }
}
