<?php

namespace Fake;

use DnsUpdater\Ip;
use DnsUpdater\Service\IpResolver\IpResolver;

class FakeIpResolver implements IpResolver
{
    /**
     * @var Ip
     */
    private $ip;

    /**
     * @return Ip
     */
    public function getIp(): Ip
    {
        return $this->ip;
    }

    /**
     * @param Ip $ip
     */
    public function setIp(Ip $ip)
    {
        $this->ip = $ip;
    }
}
