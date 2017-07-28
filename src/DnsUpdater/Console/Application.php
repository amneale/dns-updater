<?php

namespace DnsUpdater\Console;

use Symfony\Bundle\MonologBundle\DependencyInjection\Compiler\LoggerChannelPass;
use Symfony\Bundle\MonologBundle\DependencyInjection\MonologExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class Application extends BaseApplication
{
    const VERSION = '1.1.0';

    /**
     * @var ContainerBuilder
     */
    private $container;

    public function __construct()
    {
        parent::__construct('update-dns', self::VERSION);

        $this->container = new ContainerBuilder();
        $this
            ->registerMonologExtension()
            ->loadConfigurationFile()
            ->addCommand();
    }

    private function registerMonologExtension(): self
    {
        $this->container->addCompilerPass(new LoggerChannelPass());
        $this->container->registerExtension(new MonologExtension());

        return $this;
    }

    private function loadConfigurationFile(): self
    {
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
