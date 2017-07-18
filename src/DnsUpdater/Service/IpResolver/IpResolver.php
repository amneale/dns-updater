<?php

namespace DnsUpdater\Service\IpResolver;

use DnsUpdater\Ip;

interface IpResolver
{
    /**
     * @return Ip
     */
    public function getIp(): Ip;
}
