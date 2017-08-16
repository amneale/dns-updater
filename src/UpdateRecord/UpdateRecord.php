<?php

namespace DnsUpdater\UpdateRecord;

use DnsUpdater\Record;

interface UpdateRecord
{
    public function persist(Record $record): Record;
}
