<?php

declare(strict_types=1);

namespace Fake;

use DnsUpdater\Adapter\Adapter;
use DnsUpdater\Adapter\AdapterFactory;

class FakeAdapterFactory extends AdapterFactory
{
    /**
     * @var Adapter
     */
    private $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function build(string $adapter, array $params): Adapter
    {
        return $this->adapter;
    }
}
