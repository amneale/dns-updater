<?php

declare(strict_types=1);

namespace DnsUpdater\IpResolver;

use DnsUpdater\Value\IpAddress;

interface IpResolver
{
    public function getIpAddress(): IpAddress;
}
