<?php

namespace DnsUpdater\Service\RecordPersister;

use DnsUpdater\Record;

interface RecordPersister
{
    public function persist(Record $record): Record;
}
