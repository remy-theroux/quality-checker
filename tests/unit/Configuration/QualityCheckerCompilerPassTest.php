<?php

namespace QualityChecker\Configuration;

use Mockery;

/**
 * Class QualityCheckerCompilerPassTest
 *
 * @package QualityChecker\Configuration
 */
class QualityCheckerCompilerPassTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test QualityCheckerCompilerPass:process
     */
    public function testProcess()
    {
        $mockTaskRunner = Mockery::mock('QualityChecker\Task\TaskRunner');
        $mockTaskRunner
            ->shouldReceive('addMethodCall')
            ->times(2);

        $mockContainer = Mockery::mock('Symfony\Component\DependencyInjection\ContainerBuilder');
        $mockContainer
            ->shouldReceive('findDefinition')
            ->with('task_runner')
            ->andReturn($mockTaskRunner);

        $mockContainer
            ->shouldReceive('findTaggedServiceIds')
            ->with('task')
            ->andReturn([
                'phpcs' => ['fake', 'config'],
                'phpmd' => ['fake', 'config'],
            ]);

        $mockContainer
            ->shouldReceive('getParameter')
            ->with('tasks')
            ->andReturn(['phpcs', 'phpmd', 'unexisting_task']);

        $compiler = new QualityCheckerCompilerPass();
        $compiler->process($mockContainer);
    }

    public function tearDown()
    {
        Mockery::close();
    }
}
