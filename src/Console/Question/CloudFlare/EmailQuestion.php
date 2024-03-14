<?php

declare(strict_types=1);

namespace DnsUpdater\Console\Question\CloudFlare;

use Symfony\Component\Console\Question\Question;

class EmailQuestion extends Question
{
    public const QUESTION = 'What is your Email address?';

    public function __construct()
    {
        parent::__construct(self::QUESTION);
    }
}
