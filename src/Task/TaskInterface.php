<?php

namespace QualityChecker\Task;

use QualityChecker\Configuration\ConfigurationValidationException;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Interface TaskInterface
 *
 * @package QualityChecker\Task
 */
interface TaskInterface
{
    /**
     * @return array
     */
    public function getConfiguration();

    /**
     * @return array
     */
    public function getDefaultConfiguration();

    /**
     * @param OutputInterface $output Output
     *
     * @return boolean
     *
     * @throws \RuntimeException
     */
    public function run(OutputInterface $output);

    /**
     * @param array $config Configuration
     *
     * @throws ConfigurationValidationException
     *
     * @return void
     */
    public function validateConfiguration(array $config);
}
