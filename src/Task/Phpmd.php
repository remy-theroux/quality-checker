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
     * Validate configuration
     *
     * @param array $config Configuration
     *
     * @throws ConfigurationValidationException
     */
    public function validateConfiguration(array $config)
    {
        // Paths validation
        if (!isset($config['paths'])) {
            throw new ConfigurationValidationException('PHPMD configuration error : you must define a \'paths\' key');
        } elseif (!is_array($config['paths'])) {
            throw new ConfigurationValidationException('PHPMD configuration error : \'paths\' key must be an array');
        }

        // Format validation
        if (!isset($config['format'])) {
            throw new ConfigurationValidationException('PHPMD configuration error : you must define a \'format\' key');
        } elseif (!is_string($config['format'])) {
            throw new ConfigurationValidationException('PHPMD configuration error : \'format\' key must be a string');
        }

        // Rulesets validation
        if (!isset($config['rulesets'])) {
            throw new ConfigurationValidationException('PHPMD configuration error : you must define a \'rulesets\' key');
        } elseif (!is_array($config['paths'])) {
            throw new ConfigurationValidationException('PHPMD configuration error : \'rulesets\' key must be an array');
        }

        // Suffixes validation
        if (isset($config['suffixes']) && !is_array($config['suffixes'])) {
            throw new ConfigurationValidationException('PHPMD configuration error : \'suffixes\' key must be an array');
        }

        // Exclude validation
        if (isset($config['exclude']) && !is_array($config['exclude'])) {
            throw new ConfigurationValidationException('PHPMD configuration error : \'exclude\' key must be an array');
        }

        // Timeout validation
        if (isset($config['timeout']) && !is_int($config['timeout'])) {
            throw new ConfigurationValidationException('PHPMD configuration error : \'timeout\' key must be an integer');
        }

        // Strict validation
        if (isset($config['strict']) && !is_bool($config['strict'])) {
            throw new ConfigurationValidationException('PHPMD configuration error : \'strict\' key must be a boolean');
        }

        // Reportfile validation
        if (isset($config['reportfile']) && !is_string($config['reportfile'])) {
            throw new ConfigurationValidationException('PHPMD configuration error : \'reportfile\' key must be a string');
        }

        // Reportfile validation
        if (isset($config['minimumpriority']) && !is_int($config['minimumpriority'])) {
            throw new ConfigurationValidationException('PHPMD configuration error : \'minimumpriority\' key must be an integer');
        }
    }
}
