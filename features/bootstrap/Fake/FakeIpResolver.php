<?php

namespace Fake;

use DnsUpdater\IpResolver\IpResolver;
use DnsUpdater\Value\IpAddress;

class FakeIpResolver implements IpResolver
{
    /**
     * @var \DnsUpdater\Value\IpAddress
     */
    public $ipAddress;

    /**
     * @inheritdoc
     */
    public function getIpAddress(): IpAddress
    {
        return $this->ipAddress;
    }
}
