<?php

declare(strict_types=1);

namespace DnsUpdater\Console\Question;

use DnsUpdater\Adapter\CloudFlareAdapter;
use DnsUpdater\Adapter\DigitalOceanAdapter;
use Symfony\Component\Console\Question\ChoiceQuestion;

final class AdapterChoice extends ChoiceQuestion
{
    public const QUESTION = 'Which adapter would you like to use?';
    public const AVAILABLE_ADAPTERS = [
        DigitalOceanAdapter::NAME,
        CloudFlareAdapter::NAME,
    ];

    public function __construct()
    {
        parent::__construct(self::QUESTION, self::AVAILABLE_ADAPTERS);
    }
}
