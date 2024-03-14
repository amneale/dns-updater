<?php

declare(strict_types=1);

namespace spec\DnsUpdater\Adapter;

use DnsUpdater\Adapter\CloudFlareAdapter;
use DnsUpdater\Adapter\DigitalOceanAdapter;
use PhpSpec\ObjectBehavior;

class AdapterFactorySpec extends ObjectBehavior
{
    public function it_builds_digital_ocean_adapter(): void
    {
        $this->build(DigitalOceanAdapter::NAME, ['digital_ocean_access_token'])
            ->shouldBeAnInstanceOf(DigitalOceanAdapter::class);
    }

    public function it_builds_cloud_flare_adapter(): void
    {
        $this->build(CloudFlareAdapter::NAME, ['cloud_flare_email', 'cloud_flare_api_key'])
            ->shouldBeAnInstanceOf(CloudFlareAdapter::class);
    }

    public function it_throws_an_exception_if_adapter_unrecognised(): void
    {
        $this->shouldThrow(\InvalidArgumentException::class)->during('build', ['foobar', []]);
    }
}
