<?php

namespace spec\DnsUpdater\UpdateRecord;

use DnsUpdater\UpdateRecord\CloudFlareAdapter;
use DnsUpdater\UpdateRecord\DigitalOceanAdapter;
use PhpSpec\ObjectBehavior;

class AdapterFactorySpec extends ObjectBehavior
{
    function it_builds_digital_ocean_adapter()
    {
        $this->build(DigitalOceanAdapter::NAME, ['digital_ocean_access_token'])
            ->shouldBeAnInstanceOf(DigitalOceanAdapter::class);
    }

    function it_builds_cloud_flare_adapter()
    {
        $this->build(CloudFlareAdapter::NAME, ['cloud_flare_email', 'cloud_flare_api_key'])
            ->shouldBeAnInstanceOf(CloudFlareAdapter::class);
    }

    function it_throws_an_exception_if_adapter_unrecognised()
    {
        $this->shouldThrow(\InvalidArgumentException::class)->during('build', ['foobar', []]);
    }
}
