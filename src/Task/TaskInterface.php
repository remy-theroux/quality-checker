<?php

namespace QualityChecker\Task;

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
     * @param string $binDir Binary directory
     *
     * @return void
     *
     * @throws \RuntimeException
     */
    public function run(OutputInterface $output, $binDir);
}
