<?php

namespace QualityChecker\Task\Configuration;

use Mockery;
use Symfony\Component\Config\Definition\Processor;

/**
 * Class PhpmdConfigurationTest
 *
 * @package QualityChecker\Task\Configuration
 */
class PhpmdConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param array   $config  Config to validate
     * @param boolean $isValid Must config be valid
     *
     * @return array
     *
     * @dataProvider provideTestConfiguration
     */
    public function testConfiguration($config, $isValid)
    {
        if (!$isValid) {
            $this->setExpectedException('Symfony\Component\Config\Definition\Exception\InvalidConfigurationException');
        }

        $config = [
            'phpmd' => $config,
        ];

        $configuration = new PhpmdConfiguration();
        $processor     = new Processor();

        return $processor->processConfiguration(
            $configuration,
            $config
        );
    }

    /**
     * Provide testConfiguration
     *
     * @return array
     */
    public function provideTestConfiguration()
    {
        return [
            // Test paths, format & rulesets params
            [
                [],
                false,
            ],
            [
                [
                    'paths' => [],
                ],
                false,
            ],
            [
                [
                    'paths'  => [],
                    'format' => 'text',
                ],
                false,
            ],
            [
                [
                    'paths'    => [],
                    'format'   => 'text',
                    'rulesets' => 'unvalid',
                ],
                false,
            ],
            [
                [
                    'paths'    => ['./src'],
                    'format'   => 'text',
                    'rulesets' => ['cleancode'],
                ],
                true,
            ],
            // Exclude param
            [
                [
                    'paths'    => ['./src'],
                    'format'   => 'text',
                    'rulesets' => ['cleancode'],
                    'exclude'  => ['php'],
                ],
                true,
            ],
            [
                [
                    'paths'    => ['./src'],
                    'format'   => 'text',
                    'rulesets' => ['cleancode'],
                    'exclude'  => false,
                ],
                false,
            ],
            // Timeout param
            [
                [
                    'paths'    => ['./src'],
                    'format'   => 'text',
                    'rulesets' => ['cleancode'],
                    'timeout'  => 20,
                ],
                true,
            ],
            [
                [
                    'paths'    => ['./src'],
                    'format'   => 'text',
                    'rulesets' => ['cleancode'],
                    'timeout'  => [],
                ],
                false,
            ],
            // Strict param
            [
                [
                    'paths'    => ['./src'],
                    'format'   => 'text',
                    'rulesets' => ['cleancode'],
                    'strict'   => false,
                ],
                true,
            ],
            [
                [
                    'paths'    => ['./src'],
                    'format'   => 'text',
                    'rulesets' => ['cleancode'],
                    'timeout'  => [],
                ],
                false,
            ],
            // Reportfile param
            [
                [
                    'paths'      => ['./src'],
                    'format'     => 'text',
                    'rulesets'   => ['cleancode'],
                    'reportfile' => 'test.txt',
                ],
                true,
            ],
            [
                [
                    'paths'      => ['./src'],
                    'format'     => 'text',
                    'rulesets'   => ['cleancode'],
                    'reportfile' => [],
                ],
                false,
            ],
            // Minimumpriority param
            [
                [
                    'paths'           => ['./src'],
                    'format'          => 'text',
                    'rulesets'        => ['cleancode'],
                    'minimumpriority' => 5,
                ],
                true,
            ],
            [
                [
                    'paths'           => ['./src'],
                    'format'          => 'text',
                    'rulesets'        => ['cleancode'],
                    'minimumpriority' => [],
                ],
                false,
            ],
        ];
    }
}
