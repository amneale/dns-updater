<?php

namespace Fake;

use DnsUpdater\Adapter\Adapter;
use DnsUpdater\Adapter\AdapterFactory;

class FakeAdapterFactory extends AdapterFactory
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
     * @param string $adapter
     * @param array $params
     *
     * @return Adapter
     */
    public function build(string $adapter, array $params): Adapter
    {
        return $this->adapter;
    }
}
