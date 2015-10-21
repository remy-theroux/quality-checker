<?php

namespace QualityChecker\Task;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Phpcs
 *
 * @package QualityChecker\Task
 */
class Phpcs extends AbstractTask
{
    /** @const string */
    const COMMAND_NAME = 'phpcs';

    /**
     * Run task
     *
     * @param OutputInterface $output    Output
     * @param ArrayCollection $appConfig Application configuration
     */
    public function run(OutputInterface $output, $appConfig)
    {
        $output->writeln('[PHPCS] Running...');

        $config = $this->getConfiguration();

        $commandPath = $this->getCommandPath(self::COMMAND_NAME, $appConfig->get('bin_dir'));
        $this->processBuilder->setPrefix($commandPath);

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
            $output->writeln(['[PHPCS] <fg=red>Failed</fg=red>', '']);
        } else {
            $output->writeln(['[PHPCS] <fg=green>Successfull</fg=green>', '']);
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
