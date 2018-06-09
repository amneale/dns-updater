<?php

namespace DnsUpdater\Console\Question;

use Assert\Assert;
use DnsUpdater\Adapter\CloudFlareAdapter;
use DnsUpdater\Adapter\DigitalOceanAdapter;
use DnsUpdater\Console\Question\CloudFlare\ApiKeyQuestion;
use DnsUpdater\Console\Question\CloudFlare\EmailQuestion;
use DnsUpdater\Console\Question\DigitalOcean\AccessTokenQuestion;
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
