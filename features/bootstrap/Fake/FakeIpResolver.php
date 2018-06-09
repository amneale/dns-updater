<?php

namespace Fake;

use DnsUpdater\IpAddress;
use DnsUpdater\IpResolver\IpResolver;

class FakeIpResolver implements IpResolver
{
    /**
     * @var IpAddress
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
