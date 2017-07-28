<?php

use Assert\Assert;
use Behat\Behat\Context\Context;
use DnsUpdater\Command\Contract\UpdateRecordRequest;
use DnsUpdater\Command\Contract\UpdateRecordResponse;
use DnsUpdater\Command\UpdateRecord;
use DnsUpdater\IpAddress;
use DnsUpdater\Record;
use Fake\FakeIpResolver;
use Fake\FakeUpdateRecordRepository;
use Psr\Log\NullLogger;
use Symfony\Component\Cache\Simple\NullCache;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    const TEST_DOMAIN = 'foo.domain';

    /**
     * @var FakeIpResolver
     */
    private $ipResolver;

    /**
     * @var FakeUpdateRecordRepository
     */
    private $recordRepository;

    public function __construct()
    {
        $this->ipResolver = new FakeIpResolver();
        $this->recordRepository = new FakeUpdateRecordRepository();
    }

    /**
     * @Given there is an A record :host pointing at :ipAddress
     *
     * @param string $host
     * @param string $ipAddress
     */
    public function thereIsAnARecordPointingAt(string $host, string $ipAddress)
    {
        $this->recordRepository->setExistingRecords(
            array_merge(
                $this->recordRepository->getExistingRecords(),
                [new Record(self::TEST_DOMAIN, $host, Record::TYPE_ADDRESS, $ipAddress)]
            )
        );
    }

    /**
     * @Given my IP resolves to :ipAddress
     *
     * @param string $ipAddress
     */
    public function myIpResolvesTo(string $ipAddress)
    {
        $this->ipResolver->setIpAddress(new IpAddress($ipAddress));
    }

    /**
     * @When I update DNS records
     */
    public function iUpdateDnsRecords()
    {
        $updateRecordCommand = new UpdateRecord(
            $this->ipResolver,
            $this->recordRepository,
            new NullCache(),
            new NullLogger()
        );

        foreach ($this->recordRepository->getExistingRecords() as $record) {
            $updateRecordCommand->handle(new UpdateRecordRequest($record), new UpdateRecordResponse());
        }
    }

    /**
     * @Then the domain A record :host should point to :ip
     *
     * @param string $host
     * @param string $ipAddress
     */
    public function theDomainARecordShouldPointTo(string $host, string $ipAddress)
    {
        Assert::that($this->getRecord(self::TEST_DOMAIN, $host, Record::TYPE_ADDRESS)->getData())->same($ipAddress);
    }

    /**
     * @param string $domain
     * @param string $host
     * @param string $type
     *
     * @return Record
     */
    private function getRecord(string $domain, string $host, string $type): Record
    {
        $searchRecord = new Record($domain, $host, $type);

        foreach ($this->recordRepository->getExistingRecords() as $record) {
            if ($record->isSame($searchRecord)) {
                return $record;
            }
        }

        throw new InvalidArgumentException("Record not found for $host.$domain ($type)");
    }
}
