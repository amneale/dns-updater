<?php

namespace spec\DnsUpdater\Console\Question\CloudFlare;

use DnsUpdater\Console\Question\CloudFlare\ApiKeyQuestion;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Console\Question\Question;

class ApiKeyQuestionSpec extends ObjectBehavior
{
    function it_implements_question()
    {
        $this->shouldImplement(Question::class);
    }

    function it_asks_for_an_api_key()
    {
        $this->getQuestion()->shouldReturn(ApiKeyQuestion::QUESTION);
    }

    function it_should_be_hidden()
    {
        $this->shouldBeHidden();
    }
}
