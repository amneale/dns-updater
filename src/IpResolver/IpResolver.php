<?php

namespace DnsUpdater\IpResolver;

use DnsUpdater\IpAddress;

interface IpResolver
{
    /**
     * @return IpAddress
     */
    public function getIpAddress(): IpAddress;
}
