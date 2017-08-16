<?php

namespace DnsUpdater\Console\Question;

use Assert\Assert;
use DnsUpdater\Console\Question\CloudFlare\ApiKeyQuestion;
use DnsUpdater\Console\Question\CloudFlare\EmailQuestion;
use DnsUpdater\Console\Question\DigitalOcean\AccessTokenQuestion;
use DnsUpdater\UpdateRecord\CloudFlareAdapter;
use DnsUpdater\UpdateRecord\DigitalOceanAdapter;
use Symfony\Component\Console\Question\Question;

class AdapterQuestionProvider
{
    /**
     * @param string $adapter
     *
     * @return Question[]
     */
    public function getQuestionsFor(string $adapter): array
    {
        Assert::that($adapter)->choice(AdapterChoice::AVAILABLE_ADAPTERS);

        switch ($adapter) {
            case DigitalOceanAdapter::NAME:
                return [new AccessTokenQuestion()];
            case CloudFlareAdapter::NAME:
                return [
                    new EmailQuestion(),
                    new ApiKeyQuestion(),
                ];
            default:
                return [];
        }
    }
}
