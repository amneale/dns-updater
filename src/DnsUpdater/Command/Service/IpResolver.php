<?php

namespace DnsUpdater\Command\Service;

use DnsUpdater\IpAddress;

interface IpResolver
{
    /**
     * @return IpAddress
     */
    public function getIpAddress(): IpAddress;
}
