<?php

namespace QualityChecker\Task;

use QualityChecker\Configuration\ConfigurationValidationException;

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

        $commandPath = $this->getCommandPath(self::COMMAND_NAME, $this->binDir);

        $processBuilder = $this->createProcessBuilder();
        $processBuilder->setPrefix($commandPath);

        $processBuilder->setArguments([
            implode(',', $config['paths']),
            $config['format'],
            implode(',', $config['rulesets']),
        ]);

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
                'cleancode', 'codesize', 'controversial', 'design', 'naming', 'unusedcode',
            ],
            'suffixes' => ['.js'],
            'timeout'  => 180,
        ];
    }

    /**
     * @param array $config Configuration
     *
     * @throws ConfigurationValidationException
     */
    public function validateConfiguration(array $config)
    {
        // Standard validation
        if (!isset($config['standard'])) {
            throw new ConfigurationValidationException('PHPCS configuration error : you must define a \'standard\' key');
        } elseif (!is_string($config['standard'])) {
            throw new ConfigurationValidationException('PHPCS configuration error : \'standard\' key must be a string');
        }

        // Paths validation
        if (!isset($config['paths'])) {
            throw new ConfigurationValidationException('PHPCS configuration error : you must define a \'paths\' key');
        } elseif (!is_array($config['paths'])) {
            throw new ConfigurationValidationException('PHPCS configuration error : \'paths\' key must be an array');
        }

        // Show warning validation
        if (isset($config['show_warnings']) && !is_bool($config['show_warnings'])) {
            throw new ConfigurationValidationException('PHPCS configuration error : \'show_warnings\' key must be a boolean');
        }

        // Tab width validation
        if (isset($config['tab_width']) && !is_int($config['tab_width'])) {
            throw new ConfigurationValidationException('PHPCS configuration error : \'tab_width\' key must be a boolean');
        }

        // Ignore patterns validation
        if (isset($config['ignore_patterns']) && !is_array($config['ignore_patterns'])) {
            throw new ConfigurationValidationException('PHPCS configuration error : \'ignore_patterns\' key must be a boolean');
        }

        // Sniffs validation
        if (isset($config['sniffs']) && !is_array($config['sniffs'])) {
            throw new ConfigurationValidationException('PHPCS configuration error : \'sniffs\' key must be a boolean');
        }

        // Show warning validation
        if (isset($config['timeout']) && !is_int($config['timeout'])) {
            throw new ConfigurationValidationException('PHPCS configuration error : \'timeout\' key must be a boolean');
        }
    }
}
