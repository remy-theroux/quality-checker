<?php

namespace QualityChecker\Task;

use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class TaskRunner
 *
 * @package QualityChecker\Task
 */
class TaskRunner
{
    /** @var TaskInterface[] */
    private $tasks;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

    /**
     * @param TaskInterface $task Task
     *
     * @return $this
     */
    public function addTask(TaskInterface $task)
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
        }

        return $this;
    }

    /**
     * @todo Get BinDir et $output in a better way (dependency injection)
     *
     * @param OutputInterface $output    Output
     * @param ArrayCollection $appConfig Application configuration
     *
     * @return boolean
     *
     * @throws \RuntimeException
     */
    public function run(OutputInterface $output, ArrayCollection $appConfig)
    {
        $success = false;

        foreach ($this->tasks as $task) {
            $success &= $task->run($output, $appConfig);
        }

        return $success;
    }
}
