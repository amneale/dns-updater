<?php

declare(strict_types=1);

namespace spec\DnsUpdater\Console;

use DnsUpdater\Adapter\AdapterFactory;
use DnsUpdater\IpResolver\IpResolver;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Console\Application;

class ApplicationSpec extends ObjectBehavior
{
    public function it_extends_base_application(IpResolver $ipResolver, AdapterFactory $adapterFactory): void
    {
        $this->beConstructedWith($ipResolver, $adapterFactory);
        $this->shouldHaveType(Application::class);
    }
}
