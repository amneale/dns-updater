<?php

namespace DnsUpdater\Ip\Resolver;

use DnsUpdater\Ip\Ip;

interface Resolver
{
    /**
     * @return Ip
     */
    public function getIp(): Ip;
}
