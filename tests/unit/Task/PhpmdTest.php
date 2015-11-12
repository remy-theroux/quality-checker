<?php

namespace QualityChecker\Task;

use Mockery;

/**
 * Class PhpmdTest
 *
 * @package QualityChecker\Task
 */
class PhpmdTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * @test Phpmd::run
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
        $mockProcessBuilder->shouldReceive('setArguments')->with(['./src,./vendor', 'text', 'cleancode,codesize,controversial']);
        $mockProcessBuilder->shouldReceive('getProcess')->andReturn($mockProcess);
        $mockProcessBuilder->shouldReceive('setPrefix');
        $mockProcessBuilder->shouldReceive('add')->with('--minimumpriority=5');
        $mockProcessBuilder->shouldReceive('add')->with('--reportfile=./report.txt');
        $mockProcessBuilder->shouldReceive('add')->with('--exclude js,php');
        $mockProcessBuilder->shouldReceive('add')->with('--strict');
        $mockProcessBuilder->shouldReceive('add')->with('--suffixes .js,.php');

        $mockOutput = Mockery::mock('Symfony\Component\Console\Output\OutputInterface');
        $mockOutput->shouldReceive('writeln');

        $config = [
            'phpmd' => [
                'paths'           => ['./src', './vendor'],
                'format'          => 'text',
                'rulesets'        => [
                    'cleancode',
                    'codesize',
                    'controversial',
                ],
                'suffixes'        => ['.js', '.php'],
                'minimumpriority' => 5,
                'reportfile'      => './report.txt',
                'exclude'         => ['js', 'php'],
                'strict'          => true,
                'timeout'         => 180,
            ],
        ];
        $binDir = 'vendor/bin';

        $phpmd = new Phpmd($config, $binDir, $mockProcessBuilder);
        $phpmd->run($mockOutput);
    }

    /**
     * @test Phpmd::validateConfiguration
     */
    public function provideValidateConfiguration()
    {
        return [
            // Test paths, format & rulesets params
            [
                [],
                true,
            ],
            [
                [
                    'paths' => [],
                ],
                true,
            ],
            [
                [
                    'paths'  => [],
                    'format' => 'text',
                ],
                true,
            ],
            [
                [
                    'paths'    => [],
                    'format'   => 'text',
                    'rulesets' => 'unvalid',
                ],
                true,
            ],
            [
                [
                    'paths'    => ['./src'],
                    'format'   => 'text',
                    'rulesets' => ['cleancode'],
                ],
                false,
            ],
            // Exclude param
            [
                [
                    'paths'    => ['./src'],
                    'format'   => 'text',
                    'rulesets' => ['cleancode'],
                    'exclude'  => ['php'],
                ],
                false,
            ],
            [
                [
                    'paths'    => ['./src'],
                    'format'   => 'text',
                    'rulesets' => ['cleancode'],
                    'exclude'  => false,
                ],
                true,
            ],
            // Timeout param
            [
                [
                    'paths'    => ['./src'],
                    'format'   => 'text',
                    'rulesets' => ['cleancode'],
                    'timeout'  => 20,
                ],
                false,
            ],
            [
                [
                    'paths'    => ['./src'],
                    'format'   => 'text',
                    'rulesets' => ['cleancode'],
                    'timeout'  => [],
                ],
                true,
            ],
            // Strict param
            [
                [
                    'paths'    => ['./src'],
                    'format'   => 'text',
                    'rulesets' => ['cleancode'],
                    'strict'   => false,
                ],
                false,
            ],
            [
                [
                    'paths'    => ['./src'],
                    'format'   => 'text',
                    'rulesets' => ['cleancode'],
                    'timeout'  => [],
                ],
                true,
            ],
            // Reportfile param
            [
                [
                    'paths'      => ['./src'],
                    'format'     => 'text',
                    'rulesets'   => ['cleancode'],
                    'reportfile' => 'test.txt',
                ],
                false,
            ],
            [
                [
                    'paths'      => ['./src'],
                    'format'     => 'text',
                    'rulesets'   => ['cleancode'],
                    'reportfile' => [],
                ],
                true,
            ],
            // Minimumpriority param
            [
                [
                    'paths'           => ['./src'],
                    'format'          => 'text',
                    'rulesets'        => ['cleancode'],
                    'minimumpriority' => 5,
                ],
                false,
            ],
            [
                [
                    'paths'           => ['./src'],
                    'format'          => 'text',
                    'rulesets'        => ['cleancode'],
                    'minimumpriority' => [],
                ],
                true,
            ],
        ];
    }
}
