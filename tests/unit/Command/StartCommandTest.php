<?php

namespace QualityChecker\Command;

use Mockery;
use Monolog\Logger;
use QualityChecker\Task\TaskRunner;

/**
 * Class StartCommandTest
 *
 * @package QualityChecker\Command
 */
class StartCommandTest extends \PHPUnit_Framework_TestCase
{
    /** @var StartCommand $command */
    protected $command;

    /** @var Mockery\Mock $logger */
    protected $logger;

    /** @var Mockery\Mock $taskRunner */
    protected $taskRunner;


    public function setUp()
    {
        $this->command = new StartCommand(
            $this->getTaskRunner(),
            $this->getLogger()
        );
    }

    /**
     * @return Mockery\Mock|Mockery\MockInterface
     */
    protected function getLogger()
    {
        if (!$this->logger) {
            $this->logger = Mockery::mock('Monolog\Logger');
        }

        return $this->logger;

    }

    /**
     * @return Mockery\Mock|Mockery\MockInterface
     */
    protected function getTaskRunner()
    {
        if (!$this->taskRunner) {
            $this->taskRunner = Mockery::mock('QualityChecker\Task\TaskRunner');
        }

        return $this->taskRunner;
    }

    /**
     * Test constructor default value
     */
    public function testConstruct()
    {
        $name = $this->command->getName();
        $this->assertNotEmpty($name);

        $description = $this->command->getDescription();
        $this->assertNotEmpty($description);

        $this->assertSame($this->logger, $this->command->getLogger());
        $this->assertSame($this->taskRunner, $this->command->getTaskRunner());
    }

    public function testExecute()
    {
        $mockInput = Mockery::mock('Symfony\Component\Console\Input\InputInterface');
        $mockInput->shouldReceive('bind', 'isInteractive', 'validate');

        $mockOuput = Mockery::mock('Symfony\Component\Console\Output\OutputInterface');

        $this
            ->getLogger()
            ->shouldReceive('info')
            ->twice();

        $this
            ->getTaskRunner()
            ->shouldReceive('run')
            ->with($mockOuput)
            ->once()
            ->andReturn(0);

        $this->command->run($mockInput, $mockOuput);
    }
}
