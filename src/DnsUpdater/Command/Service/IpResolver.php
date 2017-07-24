<?php

namespace DnsUpdater\Command\Service;

use DnsUpdater\Ip;

interface IpResolver
{
    /**
     * @return Ip
     */
    public function getIp(): Ip;
}
