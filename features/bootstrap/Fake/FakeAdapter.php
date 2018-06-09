<?php

namespace Fake;

use DnsUpdater\Adapter\Adapter;
use DnsUpdater\Value\Record;

class FakeAdapter implements Adapter
{
    /**
     * @var Record[]
     */
    public $existingRecords = [];

    /**
     * @inheritdoc
     */
    public function persist(Record $record): void
    {
        foreach ($this->existingRecords as $key => $existingRecord) {
            if (
                $existingRecord->getDomain() === $record->getDomain() &&
                $existingRecord->getName() === $record->getName() &&
                $existingRecord->getType() === $record->getType()
            ) {
                $this->existingRecords[$key] = $record;

                return;
            }
        }

        $this->existingRecords[] = $record;
    }
}
