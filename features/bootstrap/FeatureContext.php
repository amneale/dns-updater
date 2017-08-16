<?php

use Assert\Assert;
use Behat\Behat\Context\Context;
use DnsUpdater\IpAddress;
use DnsUpdater\Record;
use Fake\FakeIpResolver;
use Fake\FakeUpdateRecord;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    /**
     * @var FakeUpdateRecord
     */
    private $recordRepository;

    /**
     * @var FakeIpResolver
     */
    private $ipResolver;

    public function __construct()
    {
        $this->recordRepository = new FakeUpdateRecord();
        $this->ipResolver = new FakeIpResolver();
    }

    /**
     * @Given there are no existing domain records
     */
    public function thereAreNoExistingDomainRecords()
    {
        $this->recordRepository->setExistingRecords([]);
    }

    /**
     * @Given there is the A record :name for domain :domain with the value :value
     *
     * @param string $name
     * @param string $domain
     * @param string $value
     */
    public function thereIsTheARecordForDomainWithTheValue(string $name, string $domain, string $value)
    {
        $this->recordRepository->setExistingRecords(
            array_merge(
                $this->recordRepository->getExistingRecords(),
                [new Record($name, $domain, Record::TYPE_ADDRESS, $value)]
            )
        );
    }

    /**
     * @Given my IP resolves as :ip
     *
     * @param string $ip
     */
    public function myIpResolvesAs(string $ip)
    {
        $this->ipResolver->setIpAddress(new IpAddress($ip));
    }

    /**
     * @When /^I update the A record "(.*?)" for domain "(.*?)"(?: with the value "(.*?)")?$/
     *
     * @param string $name
     * @param string $domain
     * @param string $value
     */
    public function iUpdateTheARecordWithTheValue(string $name, string $domain, string $value = null)
    {
        // TODO leverage command to do this?
        $value = $value ?? (string) $this->ipResolver->getIpAddress();

        $this->recordRepository->persist(
            new Record($domain, $name, Record::TYPE_ADDRESS, $value)
        );
    }

    /**
     * @Then there should exist the A record :name for domain :domain with the value :value
     *
     * @param string $name
     * @param string $domain
     * @param string $value
     */
    public function thereShouldExistTheDomainARecordWithTheValue(string $name, string $domain, string $value)
    {
        $record = $this->recordRepository->find(new Record($domain, $name, Record::TYPE_ADDRESS, $value));

        Assert::that($record)->notNull();
        Assert::that($record->getValue())->same($value);
    }
}
