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
            implode(',', $config['paths']),
            $config['format'],
            implode(',', $config['rulesets'])
        ]);

        if (isset($config['minimumpriority'])) {
            $this->processBuilder->add('--minimumpriority=' . $config['minimumpriority']);
        }

        if (isset($config['reportfile'])) {
            $this->processBuilder->add('--reportfile=' . $config['reportfile']);
        }

        if (isset($config['suffixes'])) {
            echo '--suffixes=' . implode(',', $config['suffixes']);
            $this->processBuilder->add('--suffixes ' . implode(',', $config['suffixes']));
        }

        if (isset($config['exclude'])) {
            $this->processBuilder->add('--exclude ' . implode(',', $config['exclude']));
        }

        if (isset($config['strict']) && !empty($config['strict'])) {
            $this->processBuilder->add('--strict');
        }

        $process = $this->processBuilder->getProcess();
        $process->enableOutput();
        $process->run();

        if (!$process->isSuccessful()) {
            $output->write($process->getOutput());
            $output->writeln(['[PHPMD] <fg=red>Failed</fg=red>', '']);

            return false;
        }

        $output->writeln(['[PHPMD] <fg=green>Success</fg=green>', '']);

        return true;
    }

    /**
     * @return array
     */
    public function getDefaultConfiguration()
    {
        return [
            'format'   => 'text',
            'rulesets' => [
                'cleancode', 'codesize', 'controversial', 'design', 'naming', 'unusedcode'
            ],
            'suffixes' => ['.js'],
        ];
    }
}
