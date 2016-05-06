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

    /**
     *
     */
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
     * Run all configured tasks
     *
     * @param OutputInterface $output Output
     *
     * @return boolean
     *
     * @throws \RuntimeException
     */
    public function run(OutputInterface $output)
    {
        $isSuccess = true;

        foreach ($this->tasks as $task) {
            try {
                $isSuccess = $isSuccess && $task->run($output);
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }

        return $isSuccess;
    }

    /**
     * Get property tasks
     *
     * @return TaskInterface[]
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * Set property tasks
     *
     * @param TaskInterface[] $tasks
     *
     * @return $this
     */
    public function setTasks($tasks)
    {
        $this->tasks = $tasks;

        return $this;
    }
}
