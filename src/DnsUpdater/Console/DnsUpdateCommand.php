<?php

namespace DnsUpdater\Console;

use DnsUpdater\Command\Contract\UpdateRecordRequest;
use DnsUpdater\Command\Contract\UpdateRecordResponse;
use DnsUpdater\Command\UpdateRecord;
use DnsUpdater\Record;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DnsUpdateCommand extends Command
{
    /**
     * @var UpdateRecord
     */
    private $updateRecord;

    /**
     * @var string
     */
    private $domain;

    /**
     * @var string[]
     */
    private $hosts;

    /**
     * @param UpdateRecord $updateRecord
     * @param string $domain
     * @param string[] $hosts
     */
    public function __construct(
        UpdateRecord $updateRecord,
        string $domain = null,
        array $hosts
    ) {
        $this->updateRecord = $updateRecord;
        $this->domain = $domain;
        $this->hosts = $hosts;

        parent::__construct();
    }

    /**
     * @inheritdoc
     */
    public function configure()
    {
        $this->setName('dns:update')->setDescription('Updates DNS records');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        foreach ($this->hosts as $host) {
            $this->updateRecord->handle(
                new UpdateRecordRequest(
                    new Record($this->domain, $host, Record::TYPE_ADDRESS)
                ),
                new UpdateRecordResponse()
            );
        }
    }
}
