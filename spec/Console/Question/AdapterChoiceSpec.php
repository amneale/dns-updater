<?php

namespace spec\DnsUpdater\Console\Question;

use DnsUpdater\Console\Question\AdapterChoice;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Console\Question\ChoiceQuestion;

class AdapterChoiceSpec extends ObjectBehavior
{
    function it_implements_choice_question()
    {
        $this->shouldImplement(ChoiceQuestion::class);
    }

    function it_asks_which_adapter_should_be_used()
    {
        $this->getQuestion()->shouldReturn(AdapterChoice::QUESTION);
    }

    function it_has_autocomplete_values()
    {
        $this->getAutocompleterValues()->shouldReturn(AdapterChoice::AVAILABLE_ADAPTERS);
    }
}
