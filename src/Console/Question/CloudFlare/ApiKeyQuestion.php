<?php

namespace DnsUpdater\Console\Question\CloudFlare;

use Symfony\Component\Console\Question\Question;

final class ApiKeyQuestion extends Question
{
    const QUESTION = 'What is your API Key?';

    public function __construct()
    {
        parent::__construct(self::QUESTION);
        $this->setHidden(true);
    }
}
