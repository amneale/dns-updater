<?php

declare(strict_types=1);

namespace Fake;

use DnsUpdater\IpResolver\IpResolver;
use DnsUpdater\Value\IpAddress;

class FakeIpResolver implements IpResolver
{
    /**
     * @var IpAddress
     */
    public $ipAddress;

    public function getIpAddress(): IpAddress
    {
        return $this->ipAddress;
    }
}
