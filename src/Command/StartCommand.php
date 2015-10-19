<?php

namespace QualityChecker\Command;

use QualityChecker\Exception\ConfigException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;

use Pimple\Container;

/**
 * Class CheckQualityCommand
 *
 * @package QualityChecker\Command
 */
class StartCommand extends Command
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        parent::__construct();
        $this->container = $container;
    }

    /**
     * Configure command
     */
    protected function configure()
    {
        $this
            ->setName('start')
            ->setDescription('Start all configured quality jobs, ready to write good code');
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
        $config = $this->container->offsetGet('config');
        if (!isset($config['qualitychecker']['config_file_name'])) {
            throw new \Exception('Can\'t find config entry qualitychecker.config_file_name');
        }

        $currentPath = getcwd();
        $filePath = $currentPath . '/' . $config['qualitychecker']['config_file_name'];

        if (!is_readable($filePath)) {
            $message = sprintf('Can\'t find or read config file: %s', $filePath);
            throw new \Exception($message);
        }

        // Parse yaml config file
        $yaml = new Parser();

        // Enrich exception message
        try {
            $value = $yaml->parse(file_get_contents($filePath));
        } catch (ParseException $e) {
            $message = sprintf('Unable to parse the YAML file :  %s. Error: %s', $filePath, $e->getMessage());
            throw new ConfigException($message);
        }

        // Launch each configured job

    }
}
