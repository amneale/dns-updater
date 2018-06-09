<?php

namespace DnsUpdater;

use DnsUpdater\Adapter\Adapter;
use DnsUpdater\Value\Record;

class DnsUpdater
{
    /**
     * @var Adapter
     */
    private $adapter;

    /**
     * @param Adapter $adapter
     */
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @param Record $record
     */
    public function persist(Record $record): void
    {
        $this->adapter->persist($record);
    }
}
