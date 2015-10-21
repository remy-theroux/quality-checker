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
     * @param string $binDir Binary directory
     */
    public function run(OutputInterface $output, $binDir)
    {
        $output->writeln('Running PHPCS...');

        $config = $this->getConfiguration();

        $this->processBuilder->setPrefix($binDir . DIRECTORY_SEPARATOR . self::COMMAND_NAME);
        $this->processBuilder->setArguments([
            '--standard=' . $config['standard'],
        ]);

        $this->processBuilder->add('--colors');

        if (!$config['show_warnings']) {
            $this->processBuilder->add('--warning-severity=0');
        }

        if ($config['tab_width']) {
            $this->processBuilder->add('--tab-width=' . $config['tab_width']);
        }

        if (count($config['sniffs'])) {
            $this->processBuilder->add('--sniffs=' . implode(',', $config['sniffs']));
        }

        if (count($config['ignore_patterns'])) {
            $this->processBuilder->add('--ignore=' . implode(',', $config['ignore_patterns']));
        }

        $files = $this->config['paths'];
        foreach ($files as $file) {
            $this->processBuilder->add($file);
        }

        $process = $this->processBuilder->getProcess();
        $process->enableOutput();
        $process->run();

        if (!$process->isSuccessful()) {
            $output->write($process->getOutput());
        } else {
            $output->writeln(['PHPCS successfull', '', '']);
        }
    }

    /**
     * @return array
     */
    public function getDefaultConfiguration()
    {
        return [
            'standard'        => 'PSR2',
            'show_warnings'   => true,
            'tab_width'       => null,
            'ignore_patterns' => [],
            'sniffs'          => [],
        ];
    }
}
