<?php

namespace Fake;

use DnsUpdater\IpAddress;
use DnsUpdater\IpResolver\IpResolver;

class FakeIpResolver implements IpResolver
{
    /**
     * @var IpAddress
     */
    private $ipAddress;

    /**
     * @return IpAddress
     */
    public function getIpAddress(): IpAddress
    {
        return $this->ipAddress;
    }

    /**
     * @param IpAddress $ipAddress
     */
    public function setIpAddress(IpAddress $ipAddress)
    {
        $this->ipAddress = $ipAddress;
    }
}
