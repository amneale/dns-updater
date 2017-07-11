<?php

namespace IpResolution\Resolver;

use IpResolution\Ip;

interface Resolver
{
    /**
     * @return Ip
     */
    public function getIp(): Ip;
}
