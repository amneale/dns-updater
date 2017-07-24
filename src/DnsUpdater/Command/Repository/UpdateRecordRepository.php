<?php

namespace DnsUpdater\Command\Repository;

use DnsUpdater\Record;

interface UpdateRecordRepository
{
    public function persist(Record $record): Record;
}
