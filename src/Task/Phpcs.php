<?php

namespace QualityChecker\Task;

use QualityChecker\Configuration\ConfigurationValidationException;

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
     * @param OutputInterface $output Output
     *
     * @return boolean
     */
    public function run(OutputInterface $output)
    {
        $output->writeln('[PHPCS] Running...');

        $config = $this->getConfiguration();

        $commandPath    = $this->getCommandPath(self::COMMAND_NAME, $this->binDir);
        $processBuilder = $this->createProcessBuilder();
        $processBuilder->setPrefix($commandPath);

        $processBuilder->setArguments([
            '--standard=' . $config['standard'],
        ]);

        $processBuilder->add('--colors');

        if (!$config['show_warnings']) {
            $processBuilder->add('--warning-severity=0');
        }

        if ($config['tab_width']) {
            $processBuilder->add('--tab-width=' . $config['tab_width']);
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
            $output->write($process->getOutput());
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
            'tab_width'       => null,
            'ignore_patterns' => [],
            'sniffs'          => [],
            'timeout'         => 180,
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
        } elseif (count($config['paths']) == 0) {
            throw new ConfigurationValidationException('PHPCS configuration error : \'paths\' key must be a non empty array');
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
