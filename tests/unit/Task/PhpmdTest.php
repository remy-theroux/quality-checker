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
        $mockProcessBuilder = Mockery::mock('Symfony\Component\Process\ProcessBuilder');

        $mockProcessBuilder->shouldReceive('setPrefix');

        $mockProcessBuilder
            ->shouldReceive('setArguments')
            ->with('./src,./vendor text cleancode,codesize,controversial');

        $mockProcessBuilder
            ->shouldReceive('add')
            ->with('--minimumpriority=');

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
            'paths'    => ['./src', './vendor'],
            'format'   => 'text',
            'rulesets' => [
                'cleancode', 'codesize', 'controversial',
            ],
            'suffixes' => ['.js'],
            'minimumpriority' => 5,
            'reportfile' => './report.txt',
            'exclude' => ['js', 'php'],
            'strict'  => true,
            'timeout' => 180,
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
            'format'   => 'text',
            'rulesets' => [
                'cleancode', 'codesize', 'controversial', 'design', 'naming', 'unusedcode',
            ],
            'suffixes' => ['.js'],
            'timeout'  => 180,
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
