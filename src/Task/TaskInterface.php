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
     * @param OutputInterface $output Output
     *
     * @return boolean
     *
     * @throws \RuntimeException
     */
    public function run(OutputInterface $output);
}
