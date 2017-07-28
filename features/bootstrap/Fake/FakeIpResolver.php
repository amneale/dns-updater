<?php

namespace Fake;

use DnsUpdater\Command\Service\IpResolver;
use DnsUpdater\IpAddress;

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
