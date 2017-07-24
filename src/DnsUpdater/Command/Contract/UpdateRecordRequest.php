<?php

namespace DnsUpdater\Command\Contract;

use DnsUpdater\Record;

class UpdateRecordRequest
{
    /**
     * @var Record
     */
    private $record;

    /**
     * @param Record $record
     */
    public function __construct(Record $record)
    {
        $this->record = $record;
    }

    /**
     * @return Record
     */
    public function getRecord(): Record
    {
        return $this->record;
    }
}
