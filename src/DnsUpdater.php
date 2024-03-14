<?php

declare(strict_types=1);

namespace DnsUpdater;

use DnsUpdater\Adapter\Adapter;
use DnsUpdater\Value\Record;

class DnsUpdater
{
    public function __construct(private readonly Adapter $adapter) {}

    public function persist(Record $record): void
    {
        $this->adapter->persist($record);
    }
}
