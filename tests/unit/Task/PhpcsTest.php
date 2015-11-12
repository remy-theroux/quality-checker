<?php

namespace QualityChecker\Task;

use Mockery;

/**
 * Class PhpcsTest
 *
 * @package QualityChecker\Task
 */
class PhpcsTest extends \PHPUnit_Framework_TestCase
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
        $mockProcessBuilder->shouldReceive('setArguments')->with(['--standard=PSR2']);
        $mockProcessBuilder->shouldReceive('getProcess')->andReturn($mockProcess);
        $mockProcessBuilder->shouldReceive('setPrefix');
        $mockProcessBuilder->shouldReceive('add')->with('--colors');
        $mockProcessBuilder->shouldReceive('add')->with('--warning-severity=0');
        $mockProcessBuilder->shouldReceive('add')->with('--tab-width=4');
        $mockProcessBuilder->shouldReceive('add')->with('--sniffs=Sniffs1,Sniffs2');
        $mockProcessBuilder->shouldReceive('add')->with('--ignore=*.log,.gitignore');
        $mockProcessBuilder->shouldReceive('add')->with('--ignore=*.log,.gitignore');
        $mockProcessBuilder->shouldReceive('add')->with('src');
        $mockProcessBuilder->shouldReceive('add')->with('vendor');

        $mockOutput = Mockery::mock('Symfony\Component\Console\Output\OutputInterface');
        $mockOutput->shouldReceive('writeln');

        $config = [
            'phpcs' => [
                'paths'           => ['src', 'vendor'],
                'standard'        => 'PSR2',
                'show_warnings'   => false,
                'tab_width'       => 4,
                'ignore_patterns' => ['*.log', '.gitignore'],
                'sniffs'          => ['Sniffs1', 'Sniffs2'],
                'timeout'         => 180,
            ],
        ];

        $binDir     = 'vendor/bin';

        $phpcs = new Phpcs($config, $binDir, $mockProcessBuilder);
        $phpcs->run($mockOutput);
    }

    /**
     * @test Phpcs::validateConfiguration
     */
    public function provideValidateConfiguration()
    {
        return [
            [
                [],
                true,
            ],
            [
                [
                    'standard' => []
                ],
                true,
            ],
            [
                [
                    'paths' => ''
                ],
                true,
            ],
            [
                [
                    'standard'      => 'PSR2',
                    'paths'         => ['.src'],
                    'show_warnings' => 5,
                ],
                true,
            ],
            [
                [
                    'standard'      => 'PSR2',
                    'paths'         => ['.src'],
                    'show_warnings' => true,
                    'tab_width'     => 'non integer value',
                ],
                true,
            ],
            [
                [
                    'standard'        => 'PSR2',
                    'paths'           => ['.src'],
                    'show_warnings'   => true,
                    'tab_width'       => 5,
                    'ignore_patterns' => '',
                ],
                true,
            ],
            [
                [
                    'standard'        => 'PSR2',
                    'paths'           => ['.src'],
                    'show_warnings'   => true,
                    'tab_width'       => 5,
                    'ignore_patterns' => ['php'],
                    'sniffs'          => 6,
                ],
                true,
            ],
            [
                [
                    'standard'        => 'PSR2',
                    'paths'           => ['.src'],
                    'show_warnings'   => true,
                    'tab_width'       => 5,
                    'ignore_patterns' => ['php'],
                    'sniffs'          => ['Sniff1', 'Sniff2'],
                    'timeout'         => true,
                ],
                true,
            ],
            [
                [
                    'standard'        => 'PSR2',
                    'paths'           => ['.src'],
                    'show_warnings'   => true,
                    'tab_width'       => 5,
                    'ignore_patterns' => ['php'],
                    'sniffs'          => ['Sniff1', 'Sniff2'],
                    'timeout'         => 120,
                ],
                false,
            ],
        ];
    }
}
