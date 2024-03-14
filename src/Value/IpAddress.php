<?php

declare(strict_types=1);

namespace DnsUpdater\Value;

use Assert\Assert;

class IpAddress
{
    private string $ipAddress;

    public function __construct(string $ipAddress)
    {
        $ipAddress = trim($ipAddress);
        Assert::that($ipAddress)->regex('/^(?:\d{1,3}\.){3}\d{1,3}$/', 'Invalid IP');

        $this->ipAddress = $ipAddress;
    }

    public function __toString(): string
    {
        return $this->ipAddress;
    }
}
