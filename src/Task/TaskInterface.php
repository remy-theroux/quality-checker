<?php

namespace QualityChecker\Task;

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
     *
     * @param array $config
     *
     * @return void
     * @throws \RuntimeException
     */
    public function run(array $config);
}
