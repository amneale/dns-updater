<?php

use Assert\Assert;
use Behat\Behat\Context\Context;
use DnsUpdater\Console\DnsUpdateCommand;
use DnsUpdater\IpAddress;
use DnsUpdater\Record;
use Fake\FakeAdapterFactory;
use Fake\FakeIpResolver;
use Fake\FakeUpdateRecord;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

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

    /**
     * @var DnsUpdateCommand
     */
    private $command;

    public function __construct()
    {
        $this->recordRepository = new FakeUpdateRecord();
        $this->ipResolver = new FakeIpResolver();

        $this->command = new DnsUpdateCommand($this->ipResolver, new FakeAdapterFactory($this->recordRepository));
    }

    /**
     * @Given there are no existing domain records
     */
    public function thereAreNoExistingDomainRecords(): void
    {
        $this->recordRepository->existingRecords = [];
    }

    /**
     * @Given there is the A record :name for domain :domain with the value :value
     *
     * @param string $name
     * @param string $domain
     * @param string $value
     */
    public function thereIsTheARecordForDomainWithTheValue(string $name, string $domain, string $value): void
    {
        $this->recordRepository->existingRecords = array_merge(
            $this->recordRepository->existingRecords,
            [new Record($name, $domain, Record::TYPE_ADDRESS, $value)]
        );
    }

    /**
     * @Given my IP resolves as :ip
     *
     * @param string $ip
     */
    public function myIpResolvesAs(string $ip): void
    {
        $this->ipResolver->ipAddress = new IpAddress($ip);
    }

    /**
     * @When /^I update the A record "(.*?)" for domain "(.*?)"(?: with the value "(.*?)")?$/
     *
     * @param string $name
     * @param string $domain
     * @param string $value
     *
     * @throws Exception
     */
    public function iUpdateTheARecordWithTheValue(string $name, string $domain, string $value = null): void
    {
        $parameters = [
            'name' => $name,
            'domain' => $domain,
            '--value' => $value,
            '--adapter' => 'fake',
            '--params' => ['fake'],
        ];

        $input = new ArrayInput(array_filter($parameters));
        $input->setInteractive(false);

        $this->command->run($input, new NullOutput());
    }

    /**
     * @Then there should exist the A record :name for domain :domain with the value :value
     *
     * @param string $name
     * @param string $domain
     * @param string $value
     */
    public function thereShouldExistTheDomainARecordWithTheValue(string $name, string $domain, string $value): void
    {
        $record = $this->findRecord($this->recordRepository->existingRecords, $domain, $name, Record::TYPE_ADDRESS);

        Assert::that($record)->notNull();
        Assert::that($record->getValue())->same($value);
    }

    /**
     * @param Record[] $records
     * @param string $domain
     * @param string $name
     * @param string $type
     *
     * @return Record|null
     */
    private function findRecord(array $records, string $domain, string $name, string $type): ?Record
    {
        foreach ($records as $record) {
            if (
                $domain === $record->getDomain() &&
                $name === $record->getName() &&
                $type === $record->getType()
            ) {
                return $record;
            }
        }

        return null;
    }
}
