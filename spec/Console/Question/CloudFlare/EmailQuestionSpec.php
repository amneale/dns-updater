<?php

namespace spec\DnsUpdater\Console\Question\CloudFlare;

use DnsUpdater\Console\Question\CloudFlare\EmailQuestion;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Console\Question\Question;

class EmailQuestionSpec extends ObjectBehavior
{
    public function it_implements_question(): void
    {
        $this->shouldImplement(Question::class);
    }

    public function it_asks_for_an_email(): void
    {
        $this->getQuestion()->shouldReturn(EmailQuestion::QUESTION);
    }
}
