<?php

namespace DnsUpdater\Console;

use DnsUpdater\Console\Question\AdapterChoice;
use DnsUpdater\Console\Question\AdapterQuestionProvider;
use DnsUpdater\IpResolver\CanIHazIpResolver;
use DnsUpdater\Record;
use DnsUpdater\UpdateRecord\AdapterFactory;
use DnsUpdater\UpdateRecord\UpdateRecord;
use GuzzleHttp\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DnsUpdateCommand extends Command
{
    /**
     * @inheritdoc
     */
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

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $inputOutput = new SymfonyStyle($input, $output);
        $ipResolver = new CanIHazIpResolver(new Client());

        $record = new Record(
            $input->getArgument('domain'),
            $input->getArgument('name'),
            $input->getOption('type'),
            $input->getOption('value') ?? $ipResolver->getIpAddress()
        );

        $adapter = $this->getAdapter($input->getOption('adapter'), $input->getOption('params'), $inputOutput);
        $adapter->persist($record);

        $inputOutput->table(
            ['domain', 'name', 'type', 'value'],
            [
                [
                    $record->getDomain(),
                    $record->getName(),
                    $record->getType(),
                    $record->getValue(),
                ]
            ]
        );
    }

    /**
     * @param string|null $adapter
     * @param array $params
     * @param SymfonyStyle $inputOutput
     *
     * @return UpdateRecord
     */
    private function getAdapter(string $adapter = null, array $params, SymfonyStyle $inputOutput): UpdateRecord
    {
        $adapterName = $adapter ?? $inputOutput->askQuestion(new AdapterChoice());
        $adapterName = strtolower($adapterName);

        if (empty($params)) {
            $questionGenerator = new AdapterQuestionProvider();
            foreach ($questionGenerator->getQuestionsFor($adapterName) as $question) {
                $params[] = $inputOutput->askQuestion($question);
            }
        }

        $adapterFactory = new AdapterFactory();

        return $adapterFactory->build($adapterName, $params);
    }
}
