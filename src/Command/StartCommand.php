<?php

namespace QualityChecker\Command;

use Monolog\Logger;
use QualityChecker\Exception\ConfigException;
use QualityChecker\Task\TaskRunner;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;

/**
 * Class CheckQualityCommand
 *
 * @package QualityChecker\Command
 */
class StartCommand extends Command
{
    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @param ContainerBuilder $container Container builder
     * @param Logger           $logger    Logger
     */
    public function __construct(ContainerBuilder $container, Logger $logger)
    {
        parent::__construct();
        $this->container = $container;
        $this->logger    = $logger;
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
        // Check file existence
        $configFileName = $this->container->getParameter('config_file_name');
        $configFilePath = getcwd() . '/' . $configFileName;

        if (!is_readable($configFilePath)) {
            $message = sprintf('Can\'t find or read config file: %s', $configFilePath);
            throw new \Exception($message);
        }

        // Parse yaml config file
        $yaml = new Parser();

        // Enrich exception message
        try {
            $value = $yaml->parse(file_get_contents($configFilePath));
        } catch (ParseException $e) {
            $message = sprintf('Unable to parse the YAML file : %s. Error: %s', $configFilePath, $e->getMessage());
            throw new ConfigException($message);
        }

        // Launch each configured task
        $taskRunner = new TaskRunner();


        $process = new Process('ls -lsa');

        try {
            $process->mustRun();

            echo $process->getOutput();
        } catch (ProcessFailedException $e) {
            echo $e->getMessage();
        }
    }

    public function get
}
