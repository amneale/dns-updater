<?php

declare(strict_types=1);

namespace spec\DnsUpdater\Console\Question;

use DnsUpdater\Adapter\CloudFlareAdapter;
use DnsUpdater\Adapter\DigitalOceanAdapter;
use DnsUpdater\Console\Question\CloudFlare\ApiKeyQuestion;
use DnsUpdater\Console\Question\CloudFlare\EmailQuestion;
use DnsUpdater\Console\Question\DigitalOcean\AccessTokenQuestion;
use PhpSpec\ObjectBehavior;

class AdapterQuestionProviderSpec extends ObjectBehavior
{
    public function it_gets_digital_ocean_adapter_questions(): void
    {
        $this->getQuestionsFor(DigitalOceanAdapter::NAME)->shouldBeLike([new AccessTokenQuestion()]);
    }

    public function it_gets_cloud_flare_adapter_questions(): void
    {
        $this->getQuestionsFor(CloudFlareAdapter::NAME)->shouldBeLike(
            [
                new EmailQuestion(),
                new ApiKeyQuestion(),
            ]
        );
    }

    public function it_throws_exception_for_unavailable_adapter(): void
    {
        $this->shouldThrow(\InvalidArgumentException::class)->during('getQuestionsFor', ['foobar']);
    }
}
