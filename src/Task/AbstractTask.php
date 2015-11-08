<?php

namespace QualityChecker\Task;

use QualityChecker\Task\Configuration\ValidationException;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Process\ProcessBuilder;

/**
 * Class AbstractTask
 *
 * @package QualityChecker\Task
 */
abstract class AbstractTask implements TaskInterface
{
    /** @var string $binDir Binary directory */
    protected $binDir;

    /** @var array $config Task configuration */
    protected $config;

    /**
     * @param array  $config Task configuration
     * @param string $binDir Bin directory
     */
    public function __construct(array $config, $binDir)
    {
        $this->config = array_merge($this->getDefaultConfiguration(), $config);
        $this->binDir = $binDir;

        $this->validateConfiguration($this->config);
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

    /**
     * Validate task configuration
     *
     * @param array $config Task configuration
     *
     * @throws ValidationException
     */
    public function validateConfiguration(array $config)
    {
        $className = $this->getShortClassName();

        $configurationClassName = 'QualityChecker\Task\Configuration\\' . $className;
        if (!class_exists($configurationClassName)) {
            throw new ValidationException('Can\t find configuration validation class for task ' . $configurationClassName);
        }
        $configuration          = new $configurationClassName();

        $processor = new Processor();
        $processor->processConfiguration(
            $configuration,
            $config
        );
    }

    /**
     * Get class name without namespace
     *
     * @return string
     */
    public function getShortClassName()
    {
        $className = get_class($this);
        $slashPos  = strrpos($className, '\\');

        if ($slashPos) {
            $className = substr($className, $slashPos + 1);
        }

        return $className;
    }
}
