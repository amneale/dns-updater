<?php

namespace spec\DnsUpdater\Console;

use PhpSpec\ObjectBehavior;

class ApplicationSpec extends ObjectBehavior
{
    function it_extends_base_application()
    {
        $this->shouldHaveType(\Symfony\Component\Console\Application::class);
    }
}
