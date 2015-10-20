<?php

namespace QualityChecker\Task;

use Symfony\Component\Process\ProcessBuilder;

/**
 * Class AbstractTask
 *
 * @package QualityChecker\Task
 */
abstract class AbstractTask implements TaskInterface
{
    /** @var array */
    protected $config;

    /** @var  ProcessBuilder */
    protected $processBuilder;

    /**
     * @param array $config Task configuration
     */
    public function __construct(array $config)
    {
        $this->config         =  array_merge($this->getDefaultConfiguration(), $config);
        $this->processBuilder = new ProcessBuilder();
    }

    /**
     * Get task configuration
     *
     * @return array
     */
    public function getConfiguration()
    {
        return $this->config;
    }
}
