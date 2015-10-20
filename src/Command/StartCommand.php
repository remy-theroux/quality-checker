<?php

namespace QualityChecker\Command;

use Doctrine\Common\Collections\ArrayCollection;
use Monolog\Logger;
use QualityChecker\Configuration\ContainerFactory;
use QualityChecker\Task\TaskRunner;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class CheckQualityCommand
 *
 * @package QualityChecker\Command
 */
class StartCommand extends Command
{
    /**
     * @var ArrayCollection
     */
    private $config;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @param ArrayCollection $config Application configuration
     * @param Logger          $logger Logger
     */
    public function __construct(ArrayCollection $config, Logger $logger)
    {
        parent::__construct();
        $this->config = $config;
        $this->logger = $logger;
    }

    /**
     * Configure command
     */
    protected function configure()
    {
        $this
            ->setName('start')
            ->setDescription('Start all configured quality tasks, ready to write good code');
    }

    /**
     * Execute command
     *
     * @param InputInterface  $input  Input
     * @param OutputInterface $output Output
     *
     * @return void
     *
     * @throws \Exception
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        /** @var TaskRunner $taskRunner */
        $taskRunner = $container->get('task_runner');
        $taskRunner->run(
            $output,
            $this->config->get('bin_dir')
        );
    }

    /**
     * Get container
     * @todo Move this in abstract command class to let all commands use this
     *
     * @return ContainerBuilder
     */
    public function getContainer()
    {
        $containerFactory = new ContainerFactory();
        $configFilePath   = getcwd() . DIRECTORY_SEPARATOR . $this->config->get('config_file');

        return $containerFactory->buildFromConfiguration($configFilePath);
    }
}
