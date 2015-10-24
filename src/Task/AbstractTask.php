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
    /** @var string */
    protected $binDir;

    /**
     * @param array  $config Task configuration
     * @param string $binDir Bin drirectory
     */
    public function __construct(array $config, $binDir)
    {
        $this->config = array_merge($this->getDefaultConfiguration(), $config);
        $this->validateConfiguration($this->config);
        $this->binDir = $binDir;
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

    /**
     * Build full path command
     *
     * @param string $commandName command name
     * @param string $binDir      Binary directory
     *
     * @return string
     */
    public function getCommandPath($commandName, $binDir)
    {
        if (empty($binDir)) {
            return $commandName;
        }

        return $binDir . DIRECTORY_SEPARATOR . $commandName;
    }

    /**
     * @return ProcessBuilder
     */
    public function createProcessBuilder()
    {
        return new ProcessBuilder();
    }
}
