<?php

namespace QualityChecker\Command;

use Monolog\Logger;

use QualityChecker\Configuration\ContainerFactory;
use QualityChecker\Task\TaskRunner;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class CheckQualityCommand
 */
class StartCommand extends Command
{
    /** @var Logger */
    private $logger;

    /** @var TaskRunner */
    protected $taskRunner;

    /**
     * @param TaskRunner $taskRunner Task runner
     * @param Logger     $logger     Logger
     */
    public function __construct(TaskRunner $taskRunner, Logger $logger)
    {
        parent::__construct();

        $this->taskRunner = $taskRunner;
        $this->logger     = $logger;
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
     * @return int
     *
     * @throws \Exception
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->logger->info('Execute start command');

        $isSuccessfull = $this->taskRunner->run($output);

        return $isSuccessfull ? 0 : -1;
    }
}
