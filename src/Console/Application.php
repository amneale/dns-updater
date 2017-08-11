<?php

namespace DnsUpdater\Console;

use Symfony\Component\Console\Application as BaseApplication;

final class Application extends BaseApplication
{
    const VERSION = '0.2.0';

    public function __construct()
    {
        parent::__construct('update-dns', self::VERSION);

        $command = new DnsUpdateCommand();
        $this->add($command);
        $this->setDefaultCommand($command->getName(), true);
    }
}
