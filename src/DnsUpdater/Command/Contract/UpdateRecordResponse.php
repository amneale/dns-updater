<?php

namespace DnsUpdater\Command\Contract;

use DnsUpdater\Record;

class UpdateRecordResponse
{
    /**
     * @var Record
     */
    private $record;

    /**
     * @return Record
     */
    public function getRecord(): Record
    {
        return $this->record;
    }

    /**
     * @param Record $record
     */
    public function setRecord(Record $record)
    {
        $this->record = $record;
    }
}
