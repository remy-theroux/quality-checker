<?php

namespace QualityChecker\Task;

use Mockery;

/**
 * Class AbstractTasktTest
 *
 * @package QualityChecker\Task
 */
class AbstractTasktTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * test:
     */
    public function testGetCommandPath()
    {
        $config = [
            'phpcs' => [
                'paths' => ['./fake/path'],
            ],
        ];
        $task = new Phpcs($config, '');

        $path = $task->getCommandPath(Phpcs::COMMAND_NAME, './vendor/bin');
        $this->assertEquals('./vendor/bin' . DIRECTORY_SEPARATOR . Phpcs::COMMAND_NAME, $path);

        $path = $task->getCommandPath(Phpcs::COMMAND_NAME, '');
        $this->assertEquals(Phpcs::COMMAND_NAME, $path);
    }

}
