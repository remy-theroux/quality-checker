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
        $output->writeln('[PHPCS] Running...');

        $config = $this->getConfiguration();

        $commandPath    = $this->getCommandPath(self::COMMAND_NAME, $this->binDir);
        $processBuilder = $this->processBuilder;
        $processBuilder->setPrefix($commandPath);

        $processBuilder->setArguments(
            [
                '--standard=' . $config['standard'],
            ]
        );

        $processBuilder->add('--colors');

        if (!$config['show_warnings']) {
            $processBuilder->add('--warning-severity=0');
        }


        if (count($config['sniffs'])) {
            $processBuilder->add('--sniffs=' . implode(',', $config['sniffs']));
        }

        if (count($config['ignore_patterns'])) {
            $processBuilder->add('--ignore=' . implode(',', $config['ignore_patterns']));
        }

        $files = $this->config['paths'];
        foreach ($files as $file) {
            $processBuilder->add($file);
        }

        $process = $processBuilder->getProcess();
        $process->enableOutput();
        $process->setTimeout($config['timeout']);
        $process->run();

        if (!$process->isSuccessful()) {
            $output->writeln($process->getOutput());
            $output->writeln(['[PHPCS] <fg=red>Failed</fg=red>', '']);

            return false;
        }

        $output->writeln(['[PHPCS] <fg=green>Success</fg=green>', '']);

        return true;
    }

    /**
     * @return array
     */
    public function getDefaultConfiguration()
    {
        return [
            'standard'        => 'PSR2',
            'show_warnings'   => true,
            'tab_width'       => 0,
            'ignore_patterns' => [],
            'sniffs'          => [],
            'timeout'         => 180,
        ];
    }
}