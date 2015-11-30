<?php

namespace QualityChecker\Task\Configuration;

use Mockery;
use Symfony\Component\Config\Definition\Processor;

/**
 * Class PhpunitConfigurationTest
 *
 * @package QualityChecker\Task\Configuration
 */
class PhpunitConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test default configuration
     */
    public function testDefaultConfiguration()
    {
        $configuration = new PhpunitConfiguration();
        $processor     = new Processor();

        $config = [
            'phpunit' => [],
        ];

        $config = $processor->processConfiguration(
            $configuration,
            $config
        );

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
            'phpunit' => $config,
        ];

        $configuration = new PhpunitConfiguration();
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
                true,
            ],
            [
                [
                    'timeout' => 'edfsfsdf'
                ],
                false,
            ],
            [
                [
                    'timeout' => 180
                ],
                true,
            ],
        ];
    }
}
