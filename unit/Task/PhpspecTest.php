<?php

namespace QualityChecker\Task;

use Mockery;

/**
 * Class PhpspecTest
 *
 * @package QualityChecker\Task
 */
class PhpspecTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * @test Phpcs::run
     */
    public function testRun()
    {
        $mockProcess = Mockery::mock('Symfony\Component\Process\Process');
        $mockProcess->shouldReceive('setTimeout')->with(180);
        $mockProcess->shouldReceive('enableOutput');
        $mockProcess->shouldReceive('isSuccessful');
        $mockProcess->shouldReceive('getOutput');
        $mockProcess->shouldReceive('run');
        $mockProcess->shouldReceive('stop');

        $mockProcessBuilder = Mockery::mock('Symfony\Component\Process\ProcessBuilder');
        $mockProcessBuilder->shouldReceive('getProcess')->andReturn($mockProcess);
        $mockProcessBuilder->shouldReceive('setPrefix');
        $mockProcessBuilder->shouldReceive('add')->with('run');
        $mockProcessBuilder->shouldReceive('add')->with('--config=.phpspec.yml');
        $mockProcessBuilder->shouldReceive('add')->with('--verbose');
        $mockProcessBuilder->shouldReceive('add')->with('--quiet');

        $mockOutput = Mockery::mock('Symfony\Component\Console\Output\OutputInterface');
        $mockOutput->shouldReceive('writeln');

        $config = [
            'phpspec' => [
                'config'  => '.phpspec.yml',
                'timeout' => 180,
                'verbose' => true,
                'quiet'   => true,
            ],
        ];

        $binDir = 'vendor/bin';

        $phpcs = new Phpspec($config, $binDir, $mockProcessBuilder);
        $phpcs->run($mockOutput);
    }
}
