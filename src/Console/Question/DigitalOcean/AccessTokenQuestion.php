<?php

declare(strict_types=1);

namespace DnsUpdater\Console\Question\DigitalOcean;

use Symfony\Component\Console\Question\Question;

final class AccessTokenQuestion extends Question
{
    public const QUESTION = 'What is your access token?';

    public function __construct()
    {
        parent::__construct(self::QUESTION);
        $this->setHidden(true);
    }
}
