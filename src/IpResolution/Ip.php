<?php

namespace IpResolution;

use Assert\Assert;

class Ip
{
    /**
     * @var string
     */
    private $ip;

    /**
     * @param string $ip
     */
    public function __construct(string $ip)
    {
        Assert::that($ip)->regex('/^(?:\d{1,3}\.){3}\d{1,3}$/', "Invalid IP");

        $this->ip = $ip;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->ip;
    }
}
