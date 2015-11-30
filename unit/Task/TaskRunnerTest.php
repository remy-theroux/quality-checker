<?php

namespace QualityChecker\Task;

use Mockery;

/**
 * Class TaskRunnerTest
 *
 * @package QualityChecker\Task
 */
class TaskRunnerTest extends \PHPUnit_Framework_TestCase
{
    /** @var TaskRunner */
    protected $taskRunner;

    public function setUp()
    {
        $this->taskRunner = new TaskRunner();
    }

    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * @test TaskRunner:addTask
     */
    public function testAddTask()
    {
        $mockTask = Mockery::mock('QualityChecker\Task\TaskInterface');
        $this->taskRunner->addTask($mockTask);

        $this->assertCount(1, $this->taskRunner->getTasks());

        $this->taskRunner->addTask($mockTask);
        $this->assertCount(1, $this->taskRunner->getTasks());

        $mockTask2 = Mockery::mock('QualityChecker\Task\TaskInterface');
        $this->taskRunner->addTask($mockTask2);
        $this->assertCount(2, $this->taskRunner->getTasks());
    }

    /**
     * @test TaskRunner:run
     */
    public function testRun()
    {
        $mockOutput = Mockery::mock('Symfony\Component\Console\Output\OutputInterface');

        // Validate with one successfull task
        $mockTask = Mockery::mock('QualityChecker\Task\TaskInterface');
        $mockTask
            ->shouldReceive('run')
            ->with($mockOutput)
            ->andReturn(true);

        $mockTask
            ->shouldReceive('getConfiguration')
            ->andReturn([]);
        $mockTask->shouldReceive('validateConfiguration');

        $this->taskRunner->addTask($mockTask);

        $return = $this->taskRunner->run($mockOutput);
        $this->assertTrue($return);

        // Validate with two tasks and second failed
        $mockTask2 = Mockery::mock('QualityChecker\Task\TaskInterface');
        $mockTask2
            ->shouldReceive('run')
            ->with($mockOutput)
            ->andReturn(false);

        $mockTask2
            ->shouldReceive('getConfiguration')
            ->andReturn([]);
        $mockTask2->shouldReceive('validateConfiguration');

        $this->taskRunner->addTask($mockTask2);

        $return = $this->taskRunner->run($mockOutput);
        $this->assertFalse($return);
    }
}
