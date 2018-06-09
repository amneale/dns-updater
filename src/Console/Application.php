<?php

namespace DnsUpdater\Console;

use DnsUpdater\Adapter\AdapterFactory;
use DnsUpdater\IpResolver\IpResolver;
use Symfony\Component\Console\Application as BaseApplication;

final class Application extends BaseApplication
{
    const VERSION = '0.2.0';

    public function __construct(IpResolver $ipResolver, AdapterFactory $adapterFactory)
    {
        parent::__construct('update-dns', self::VERSION);

        $command = new DnsUpdateCommand($ipResolver, $adapterFactory);
        $this->add($command);
        $this->setDefaultCommand($command->getName(), true);
    }
}
