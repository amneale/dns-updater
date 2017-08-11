<?php

namespace DnsUpdater\Console\Question;

use DnsUpdater\UpdateRecord\CloudFlareAdapter;
use DnsUpdater\UpdateRecord\DigitalOceanAdapter;
use Symfony\Component\Console\Question\ChoiceQuestion;

final class AdapterChoice extends ChoiceQuestion
{
    const QUESTION = 'Which adapter would you like to use?';
    const AVAILABLE_ADAPTERS = [
        DigitalOceanAdapter::NAME,
        CloudFlareAdapter::NAME,
    ];

    public function __construct()
    {
        parent::__construct(self::QUESTION, self::AVAILABLE_ADAPTERS);
    }
}
