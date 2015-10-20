<?php

namespace QualityChecker\Task;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class TaskRunner
 *
 * @package QualityChecker\Task
 */
class TaskRunner {

    /** @var TaskInterface[] */
    private $tasks;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

    /**
     * @param TaskInterface $task
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

    public function run()
    {
        $failures = false;
        $messages = [];

        foreach ($this->tasks as $task) {
            try {
                $task->run($config);
            } catch (\RuntimeException $e) {
                $failures = true;
                $messages[] = $e->getMessage();
            }
        }

        if ($failures) {
            throw new FailureException(implode(PHP_EOL, $messages));
        }
    }
}
