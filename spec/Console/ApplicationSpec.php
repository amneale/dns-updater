<?php

namespace spec\DnsUpdater\Console;

use DnsUpdater\UpdateRecord\UpdateRecord;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Console\Application;

class ApplicationSpec extends ObjectBehavior
{
    public function it_extends_base_application(UpdateRecord $recordRepository): void
    {
        $this->beConstructedWith($recordRepository);
        $this->shouldHaveType(Application::class);
    }
}
