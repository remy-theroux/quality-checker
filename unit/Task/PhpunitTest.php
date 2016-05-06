<?php

namespace QualityChecker\Task;

use Mockery;

/**
 * Class PhpunitTest
 *
 * @package QualityChecker\Task
 */
class PhpunitTest extends \PHPUnit_Framework_TestCase
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
        $mockProcess->shouldReceive('getErrorOutput');
        $mockProcess->shouldReceive('isSuccessful');
        $mockProcess->shouldReceive('getOutput');
        $mockProcess->shouldReceive('run');
        $mockProcess->shouldReceive('stop');

        $mockProcessBuilder = Mockery::mock('Symfony\Component\Process\ProcessBuilder');
        $mockProcessBuilder->shouldReceive('getProcess')->andReturn($mockProcess);
        $mockProcessBuilder->shouldReceive('setPrefix');

        $mockOutput = Mockery::mock('Symfony\Component\Console\Output\OutputInterface');
        $mockOutput->shouldReceive('writeln');

        $config = [
            'phpcs' => [
                'timeout'         => 180,
            ],
        ];

        $binDir     = 'vendor/bin';

        $phpcs = new Phpunit($config, $binDir, $mockProcessBuilder);
        $phpcs->run($mockOutput);
    }
}
