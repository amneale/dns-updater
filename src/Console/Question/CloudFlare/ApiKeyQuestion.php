<?php

declare(strict_types=1);

namespace DnsUpdater\Console\Question\CloudFlare;

use Symfony\Component\Console\Question\Question;

final class ApiKeyQuestion extends Question
{
    public const QUESTION = 'What is your API Key?';

    public function __construct()
    {
        parent::__construct(self::QUESTION);
        $this->setHidden(true);
    }
}
