<?php

use Assert\Assert;
use Behat\Behat\Context\Context;
use DnsUpdater\Ip;
use DnsUpdater\Record;
use Fake\FakeIpResolver;
use Fake\FakeUpdateRecordRepository;

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
     * @Given there is an A record :host pointing at :ip
     *
     * @param string $host
     * @param string $ip
     */
    public function thereIsAnARecordPointingAt(string $host, string $ip)
    {
        $this->recordRepository->setExistingRecords(
            array_merge(
                $this->recordRepository->getExistingRecords(),
                [new Record(self::TEST_DOMAIN, $host, Record::TYPE_ADDRESS, $ip)]
            )
        );
    }

    /**
     * @Given my IP resolves to :ip
     *
     * @param string $ip
     */
    public function myIpResolvesTo(string $ip)
    {
        $this->ipResolver->setIp(new Ip($ip));
    }

    /**
     * @When I update DNS records
     */
    public function iUpdateDnsRecords()
    {
        foreach ($this->recordRepository->getExistingRecords() as $record) {
            $record->setData((string) $this->ipResolver->getIp());
            $this->recordRepository->persist($record);
        }
    }

    /**
     * @Then the domain A record :host should point to :ip
     *
     * @param string $host
     * @param string $ip
     */
    public function theDomainARecordShouldPointTo(string $host, string $ip)
    {
        Assert::that($this->getRecord(self::TEST_DOMAIN, $host, Record::TYPE_ADDRESS)->getData())->same($ip);
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
