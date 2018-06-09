<?php

namespace DnsUpdater\IpResolver;

use DnsUpdater\Value\IpAddress;

interface IpResolver
{
    /**
     * @return \DnsUpdater\Value\IpAddress
     */
    public function getIpAddress(): IpAddress;
}
