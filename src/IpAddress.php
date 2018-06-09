<?php

namespace DnsUpdater;

use Assert\Assert;

class IpAddress
{
    /**
     * @var string
     */
    private $ipAddress;

    /**
     * @param string $ipAddress
     */
    public function __construct(string $ipAddress)
    {
        $ipAddress = trim($ipAddress);
        Assert::that($ipAddress)->regex('/^(?:\d{1,3}\.){3}\d{1,3}$/', 'Invalid IP');

        $this->ipAddress = $ipAddress;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->ipAddress;
    }
}
