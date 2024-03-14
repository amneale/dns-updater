<?php

declare(strict_types=1);

namespace spec\DnsUpdater\Console\Question\CloudFlare;

use DnsUpdater\Console\Question\CloudFlare\ApiKeyQuestion;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Console\Question\Question;

class ApiKeyQuestionSpec extends ObjectBehavior
{
    public function it_implements_question(): void
    {
        $this->shouldImplement(Question::class);
    }

    public function it_asks_for_an_api_key(): void
    {
        $this->getQuestion()->shouldReturn(ApiKeyQuestion::QUESTION);
    }

    public function it_should_be_hidden(): void
    {
        $this->shouldBeHidden();
    }
}
