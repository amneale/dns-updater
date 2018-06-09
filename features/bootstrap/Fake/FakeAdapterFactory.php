<?php

namespace Fake;

use DnsUpdater\UpdateRecord\AdapterFactory;
use DnsUpdater\UpdateRecord\UpdateRecord;

class FakeAdapterFactory extends AdapterFactory
{
    /**
     * @var UpdateRecord
     */
    private $adapter;

    /**
     * @param UpdateRecord $adapter
     */
    public function __construct(UpdateRecord $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @param string $adapter
     * @param array $params
     *
     * @return UpdateRecord
     */
    public function build(string $adapter, array $params): UpdateRecord
    {
        return $this->adapter;
    }
}
