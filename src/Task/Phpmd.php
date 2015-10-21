<?php

namespace QualityChecker\Task;

use Doctrine\Common\Collections\ArrayCollection;

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
     * @inheritdoc
     */
    public function run(OutputInterface $output, ArrayCollection $appConfig)
    {
        $output->writeln('[PHPMD] Running...');

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
            $output->writeln(['[PHPMD] <fg=red>Failed</fg=red>', '']);
        }

        $output->writeln(['[PHPMD] <fg=green>Success</fg=green>', '']);
        return true;
    }

    /**
     * @return array
     */
    public function getDefaultConfiguration()
    {
        return [];
    }
}
