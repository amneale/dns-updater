<?php

namespace spec\DnsUpdater\Console\Question;

use DnsUpdater\Console\Question\CloudFlare\ApiKeyQuestion;
use DnsUpdater\Console\Question\CloudFlare\EmailQuestion;
use DnsUpdater\Console\Question\DigitalOcean\AccessTokenQuestion;
use DnsUpdater\UpdateRecord\CloudFlareAdapter;
use DnsUpdater\UpdateRecord\DigitalOceanAdapter;
use PhpSpec\ObjectBehavior;

class AdapterQuestionProviderSpec extends ObjectBehavior
{
    function it_gets_digital_ocean_adapter_questions()
    {
        $this->getQuestionsFor(DigitalOceanAdapter::NAME)->shouldBeLike([new AccessTokenQuestion()]);
    }

    function it_gets_cloud_flare_adapter_questions()
    {
        $this->getQuestionsFor(CloudFlareAdapter::NAME)->shouldBeLike(
            [
                new EmailQuestion(),
                new ApiKeyQuestion(),
            ]
        );
    }

    function it_throws_exception_for_unavailable_adapter()
    {
        $this->shouldThrow(\InvalidArgumentException::class)->during('getQuestionsFor', ['foobar']);
    }
}
