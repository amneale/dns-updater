<?php

namespace DnsUpdater\Adapter;

use DnsUpdater\Value\Record;

interface Adapter
{
    /**
     * @param Record $record
     */
    public function persist(Record $record): void;
}
