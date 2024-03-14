<?php

declare(strict_types=1);

namespace DnsUpdater;

use DnsUpdater\Adapter\Adapter;
use DnsUpdater\Value\Record;

class DnsUpdater
{
    /**
     * @var Adapter
     */
    private $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function persist(Record $record): void
    {
        $this->adapter->persist($record);
    }
}
