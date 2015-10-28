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
     * @test Phpcs::getCommandPath
     */
    public function testGetCommandPath()
    {
        $task = new Phpcs([], '');

        $path = $task->getCommandPath(Phpcs::COMMAND_NAME, './vendor/bin');
        $this->assertEquals('./vendor/bin' . DIRECTORY_SEPARATOR . Phpcs::COMMAND_NAME, $path);

        $path = $task->getCommandPath(Phpcs::COMMAND_NAME, '');
        $this->assertEquals(Phpcs::COMMAND_NAME, $path);
    }

    /**
     * @test Phpcs::getCommandPath
     */
    public function testRun()
    {
        $mockProcessBuilder = Mockery::mock('Symfony\Component\Process\ProcessBuilder');

        $mockProcessBuilder->shouldReceive('setPrefix');

        $mockProcessBuilder
            ->shouldReceive('setArguments')
            ->with('--standard=PSR2');

        $mockProcessBuilder
            ->shouldReceive('add')
            ->with('--colors');

        $mockProcessBuilder
            ->shouldReceive('add')
            ->with('--warning-severity=0');

        $mockProcessBuilder
            ->shouldReceive('add')
            ->with('--tab-width=4');

        $mockProcessBuilder
            ->shouldReceive('add')
            ->with('--sniffs=Sniffs1,Sniffs2');

        $mockProcessBuilder
            ->shouldReceive('add')
            ->with('--ignore=*.log,.gitignore');

        $mockProcessBuilder
            ->shouldReceive('add')
            ->with('--ignore=*.log,.gitignore');

        $mockProcessBuilder
            ->shouldReceive('add')
            ->with('src');

        $mockProcessBuilder
            ->shouldReceive('add')
            ->with('vendor');

        $config = [
            'paths'           => ['src', 'vendor'],
            'standard'        => 'PSR2',
            'show_warnings'   => false,
            'tab_width'       => 4,
            'ignore_patterns' => ['*.log', '.gitignore'],
            'sniffs'          => ['Sniffs1', 'Sniffs2'],
            'timeout'         => 180,
        ];
        $binDir = 'vendor/bin';

        $phpcs = Mockery::mock('QualityChecker\Task\Phpcs[createProcessBuilder]', [$config, $binDir]);
        $phpcs
            ->shouldReceive('createProcessBuilder')
            ->andReturn($mockProcessBuilder);
    }

    /**
     * @test Phpcs::getDefaultConfiguration
     */
    public function testGetDefaultConfiguration()
    {
        $phpcs         = new Phpcs([], '');
        $config        = $phpcs->getDefaultConfiguration();
        $defaultConfig = [
            'standard'        => 'PSR2',
            'show_warnings'   => true,
            'tab_width'       => null,
            'ignore_patterns' => [],
            'sniffs'          => [],
            'timeout'         => 180,
        ];

        $this->assertInternalType('array', $config);
        $this->assertEquals($defaultConfig, $config);
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

    /**
     *
     * @dataProvider provideValidateConfiguration
     * @test Phpcs::validateConfiguration
     */
    public function testValidateConfiguration($config, $isExceptionExpected)
    {
        $phpcs = new Phpcs([], '');

        if ($isExceptionExpected) {
            $this->setExpectedException('QualityChecker\Configuration\ConfigurationValidationException');
        }

        $phpcs->validateConfiguration($config);
    }
}
