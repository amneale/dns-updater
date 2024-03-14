<?php

declare(strict_types=1);

namespace spec\DnsUpdater\Console\Question\DigitalOcean;

use DnsUpdater\Console\Question\DigitalOcean\AccessTokenQuestion;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Console\Question\Question;

class AccessTokenQuestionSpec extends ObjectBehavior
{
    public function it_implements_question(): void
    {
        $this->shouldImplement(Question::class);
    }

    public function it_asks_for_an_access_token(): void
    {
        $this->getQuestion()->shouldReturn(AccessTokenQuestion::QUESTION);
    }

    public function it_should_be_hidden(): void
    {
        $this->shouldBeHidden();
    }
}
