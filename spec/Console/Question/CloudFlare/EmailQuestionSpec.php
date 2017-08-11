<?php

namespace spec\DnsUpdater\Console\Question\CloudFlare;

use DnsUpdater\Console\Question\CloudFlare\EmailQuestion;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Console\Question\Question;

class EmailQuestionSpec extends ObjectBehavior
{
    function it_implements_question()
    {
        $this->shouldImplement(Question::class);
    }

    function it_asks_for_an_email()
    {
        $this->getQuestion()->shouldReturn(EmailQuestion::QUESTION);
    }
}
