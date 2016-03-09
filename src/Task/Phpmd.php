<?php

namespace QualityChecker\Task;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Phpmd
 *
 * @package QualityChecker\Task
 */
class Phpmd extends AbstractTask
{
    /** @const string */
    const COMMAND_NAME = 'phpmd';

    /**
     * Run task
     *
     * @param OutputInterface $output Output
     *
     * @return boolean
     */
    public function run(OutputInterface $output)
    {
        $output->writeln('[PHPMD] Running...');

        $config = $this->getConfiguration();

        $commandPath    = $this->getCommandPath(self::COMMAND_NAME, $this->binDir);
        $processBuilder = $this->processBuilder;
        $processBuilder->setPrefix($commandPath);

        $processBuilder->setArguments(
            [
                implode(',', $config['paths']),
                $config['format'],
                implode(',', $config['rulesets']),
            ]
        );

        if (isset($config['minimumpriority'])) {
            $processBuilder->add('--minimumpriority=' . $config['minimumpriority']);
        }

        if (isset($config['reportfile'])) {
            $processBuilder->add('--reportfile=' . $config['reportfile']);
        }

        if (isset($config['suffixes'])) {
            $processBuilder->add('--suffixes ' . implode(',', $config['suffixes']));
        }

        if (isset($config['exclude'])) {
            $processBuilder->add('--exclude ' . implode(',', $config['exclude']));
        }

        if (isset($config['strict']) && !empty($config['strict'])) {
            $processBuilder->add('--strict');
        }

        $process = $processBuilder->getProcess();
        $process->enableOutput();
        $process->setTimeout($config['timeout']);
        $process->run();

        $output->writeln($process->getOutput());

        if (!$process->isSuccessful()) {
            $output->writeln($process->getErrorOutput());
            $output->writeln(['[PHPMD] <fg=red>Failed</fg=red>', '']);

            return false;
        }

        $output->writeln(['[PHPMD] <fg=green>Success</fg=green>', '']);

        return true;
    }
}
