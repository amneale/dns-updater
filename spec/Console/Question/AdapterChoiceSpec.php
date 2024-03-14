<?php

declare(strict_types=1);

namespace spec\DnsUpdater\Console\Question;

use DnsUpdater\Console\Question\AdapterChoice;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Console\Question\ChoiceQuestion;

class AdapterChoiceSpec extends ObjectBehavior
{
    public function it_implements_choice_question(): void
    {
        $this->shouldImplement(ChoiceQuestion::class);
    }

    public function it_asks_which_adapter_should_be_used(): void
    {
        $this->getQuestion()->shouldReturn(AdapterChoice::QUESTION);
    }

    public function it_has_autocomplete_values(): void
    {
        $this->getAutocompleterValues()->shouldReturn(AdapterChoice::AVAILABLE_ADAPTERS);
    }
}
