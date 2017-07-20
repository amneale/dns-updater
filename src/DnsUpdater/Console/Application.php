<?php

namespace DnsUpdater\Console;

use Symfony\Bundle\MonologBundle\DependencyInjection\Compiler\LoggerChannelPass;
use Symfony\Bundle\MonologBundle\DependencyInjection\MonologExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class Application extends BaseApplication
{
    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * @param string $version
     */
    public function __construct($version = 'UNKNOWN')
    {
        $this->container = new ContainerBuilder();

        parent::__construct('update-dns', $version);
    }

    public function doRun(InputInterface $input, OutputInterface $output)
    {
        $this
            ->registerMonologExtension()
            ->loadConfigurationFile()
            ->addCommand();

        return parent::doRun($input, $output);
    }

    private function registerMonologExtension(): self
    {
        $this->container->addCompilerPass(new LoggerChannelPass());
        $this->container->registerExtension(new MonologExtension());

        return $this;
    }

    private function loadConfigurationFile(): self
    {
        // TODO resolve path somehow
        $loader = new YamlFileLoader($this->container, new FileLocator(__DIR__ . '/../../../app/config'));
        $loader->load('config.yml');
        $this->container->compile();

        return $this;
    }

    private function addCommand(): self
    {
        $command = $this->container->get('dns_update_command');
        $this->add($command);
        $this->setDefaultCommand($command->getName(), true);

        return $this;
    }
}
