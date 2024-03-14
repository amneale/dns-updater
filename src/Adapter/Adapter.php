<?php

declare(strict_types=1);

namespace DnsUpdater\Adapter;

use DnsUpdater\Value\Record;

interface Adapter
{
    public function persist(Record $record): void;
}
