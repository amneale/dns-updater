<?php

declare(strict_types=1);

namespace DnsUpdater\Console;

use DnsUpdater\Adapter\Adapter;
use DnsUpdater\Adapter\AdapterFactory;
use DnsUpdater\Console\Question\AdapterChoice;
use DnsUpdater\Console\Question\AdapterQuestionProvider;
use DnsUpdater\DnsUpdater;
use DnsUpdater\IpResolver\IpResolver;
use DnsUpdater\Value\Record;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DnsUpdateCommand extends Command
{
    public function __construct(
        private readonly IpResolver $ipResolver,
        private readonly AdapterFactory $adapterFactory,
        ?string $name = null
    ) {
        parent::__construct($name);
    }

    public function configure(): void
    {
        $this
            ->setName('dns:update')
            ->setDescription('Updates DNS records')
            ->addArgument('domain', InputArgument::REQUIRED, 'The domain')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the record')
            ->addOption('value', null, InputOption::VALUE_REQUIRED, 'The new value of the record')
            ->addOption('type', null, InputOption::VALUE_REQUIRED, 'The type of record', Record::TYPE_ADDRESS)
            ->addOption('adapter', null, InputOption::VALUE_REQUIRED, 'The adapter to use')
            ->addOption(
                'params',
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'The parameters for the given adapter'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $inputOutput = new SymfonyStyle($input, $output);

        $record = new Record(
            $input->getArgument('domain'),
            $input->getArgument('name'),
            $input->getOption('type'),
            $input->getOption('value') ?? (string) $this->ipResolver->getIpAddress()
        );

        $dnsUpdater = new DnsUpdater(
            $this->getAdapter($input->getOption('adapter'), $input->getOption('params'), $inputOutput)
        );
        $dnsUpdater->persist($record);

        $inputOutput->table(
            ['domain', 'name', 'type', 'value'],
            [
                [
                    $record->getDomain(),
                    $record->getName(),
                    $record->getType(),
                    $record->getValue(),
                ],
            ]
        );

        return 0;
    }

    private function getAdapter(?string $adapter, array $params, SymfonyStyle $inputOutput): Adapter
    {
        $adapterName = $adapter ?? $inputOutput->askQuestion(new AdapterChoice());
        $adapterName = strtolower($adapterName);

        if (empty($params)) {
            $questionGenerator = new AdapterQuestionProvider();
            foreach ($questionGenerator->getQuestionsFor($adapterName) as $question) {
                $params[] = $inputOutput->askQuestion($question);
            }
        }

        return $this->adapterFactory->build($adapterName, $params);
    }
}
