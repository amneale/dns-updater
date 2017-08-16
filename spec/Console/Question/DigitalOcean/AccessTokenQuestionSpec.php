<?php

namespace spec\DnsUpdater\Console\Question\DigitalOcean;

use DnsUpdater\Console\Question\DigitalOcean\AccessTokenQuestion;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Console\Question\Question;

class AccessTokenQuestionSpec extends ObjectBehavior
{
    function it_implements_question()
    {
        $this->shouldImplement(Question::class);
    }

    function it_asks_for_an_access_token()
    {
        $this->getQuestion()->shouldReturn(AccessTokenQuestion::QUESTION);
    }

    function it_should_be_hidden()
    {
        $this->shouldBeHidden();
    }
}
