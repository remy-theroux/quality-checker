<?php

namespace QualityChecker\Task;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Phpspec
 *
 * @package QualityChecker\Task
 */
class Phpspec extends AbstractTask
{
    /** @const string */
    const COMMAND_NAME = 'phpspec';

    /**
     * Run task
     *
     * @param OutputInterface $output Output
     *
     * @return boolean
     */
    public function run(OutputInterface $output)
    {
        $output->writeln('[PHPSPEC] Testing...');

        $config         = $this->getConfiguration();
        $commandPath    = $this->getCommandPath(self::COMMAND_NAME, $this->binDir);
        $processBuilder = $this->processBuilder;
        $processBuilder->setPrefix($commandPath);
        $processBuilder->add('run');

        if (!empty($config['config'])) {
            $processBuilder->add('--config ' . $config['config']);
        }

        if (!empty($config['verbose'])) {
            $processBuilder->add('--verbose');
        }

        if (!empty($config['quiet'])) {
            $processBuilder->add('--quiet');
        }

        $process = $processBuilder->getProcess();
        $process->enableOutput();
        $process->setTimeout($config['timeout']);
        $process->run();

        $output->writeln($process->getOutput());

        if (!$process->isSuccessful()) {
            $output->writeln(['[PHPSPEC] <fg=red>Failed</fg=red>', '']);

            return false;
        }

        $output->writeln(['[PHPSPEC] <fg=green>Success</fg=green>', '']);

        return true;
    }
}
