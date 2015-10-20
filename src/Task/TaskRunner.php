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

    /**
     * @todo Get BinDir et $output in a better way (dependency injection)
     *
     * @param OutputInterface $output Output
     * @param string          $binDir Binary directory
     *
     * @throws \RuntimeException
     */
    public function run(OutputInterface $output, $binDir)
    {
        $failures = false;
        $messages = [];

        foreach ($this->tasks as $task) {
            try {
                $task->run($output, $binDir);
            } catch (\RuntimeException $e) {
                $failures   = true;
                $messages[] = $e->getMessage();
            }
        }

        if ($failures) {
            throw new \RuntimeException(implode(PHP_EOL, $messages));
        }
    }
}
