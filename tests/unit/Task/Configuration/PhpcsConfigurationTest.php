<?php

namespace QualityChecker\Task\Configuration;

use Mockery;
use Symfony\Component\Config\Definition\Processor;

/**
 * Class PhpcsConfigurationTest
 *
 * @package QualityChecker\Task\Configuration
 */
class PhpcsConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test default configuration
     */
    public function testDefaultConfiguration()
    {
        $configuration = new PhpcsConfiguration();
        $processor     = new Processor();

        $config = [
            'phpcs' => [
                'paths' => ['.src'],
            ],
        ];

        $config = $processor->processConfiguration(
            $configuration,
            $config
        );

        $this->assertArrayHasKey('paths', $config);
        $this->assertEquals(['.src'], $config['paths']);

        $this->assertArrayHasKey('standard', $config);
        $this->assertEquals('PSR2', $config['standard']);

        $this->assertArrayHasKey('ignore_patterns', $config);
        $this->assertEquals([], $config['ignore_patterns']);

        $this->assertArrayHasKey('sniffs', $config);
        $this->assertEquals([], $config['sniffs']);

        $this->assertArrayHasKey('show_warnings', $config);
        $this->assertFalse($config['show_warnings']);

        $this->assertArrayHasKey('timeout', $config);
        $this->assertEquals(540, $config['timeout']);
    }

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
            'phpcs' => $config,
        ];

        $configuration = new PhpcsConfiguration();
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
            [
                [],
                false,
            ],
            [
                [
                    'standard' => []
                ],
                false,
            ],
            [
                [
                    'paths' => ''
                ],
                false,
            ],
            [
                [
                    'standard'      => 'PSR2',
                    'paths'         => ['.src'],
                    'show_warnings' => 5,
                ],
                false,
            ],
            [
                [
                    'standard'      => 'PSR2',
                    'paths'         => ['.src'],
                    'show_warnings' => false,
                    'tab_width'     => 'non integer value',
                ],
                false,
            ],
            [
                [
                    'standard'        => 'PSR2',
                    'paths'           => ['.src'],
                    'show_warnings'   => false,
                    'tab_width'       => 5,
                    'ignore_patterns' => '',
                ],
                false,
            ],
            [
                [
                    'standard'        => 'PSR2',
                    'paths'           => ['.src'],
                    'show_warnings'   => false,
                    'tab_width'       => 5,
                    'ignore_patterns' => ['php'],
                    'sniffs'          => 6,
                ],
                false,
            ],
            [
                [
                    'standard'        => 'PSR2',
                    'paths'           => ['.src'],
                    'show_warnings'   => false,
                    'tab_width'       => 5,
                    'ignore_patterns' => ['php'],
                    'sniffs'          => ['Sniff1', 'Sniff2'],
                    'timeout'         => false,
                ],
                false,
            ],
            [
                [
                    'standard'        => 'PSR2',
                    'paths'           => ['.src'],
                    'show_warnings'   => false,
                    'tab_width'       => 5,
                    'ignore_patterns' => ['php'],
                    'sniffs'          => ['Sniff1', 'Sniff2'],
                    'timeout'         => 120,
                ],
                true,
            ],
        ];
    }
}
