<?php

namespace QualityChecker\Task;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Phpunit
 *
 * @package QualityChecker\Task
 */
class Phpunit extends AbstractTask
{
    /** @const string */
    const COMMAND_NAME = 'phpunit';

    /**
     * Run task
     *
     * @param OutputInterface $output Output
     *
     * @return boolean
     */
    public function run(OutputInterface $output)
    {
        $output->writeln('[PHPUNIT] Testing...');

        $config         = $this->getConfiguration();
        $commandPath    = $this->getCommandPath(self::COMMAND_NAME, $this->binDir);
        $processBuilder = $this->processBuilder;
        $processBuilder->setPrefix($commandPath);

        $process = $processBuilder->getProcess();
        $process->enableOutput();
        $process->setTimeout($config['timeout']);
        $process->run();

        $output->writeln($process->getOutput());

        if (!$process->isSuccessful()) {
            $output->writeln(['[PHPUNIT] <fg=red>Failed</fg=red>', '']);
            $output->writeln(['[PHPUNIT] ' . $process->getErrorOutput(), '']);

            return false;
        }

        $output->writeln(['[PHPUNIT] <fg=green>Success</fg=green>', '']);

        return true;
    }
}
