<?php

namespace QualityChecker\Task;

use Mockery;

/**
 * Class AbstractTest
 *
 * @package QualityChecker\Task
 */
class AbstractTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testGetCommandPath()
    {
        $task = new Phpcs([], '');

        $path = $task->getCommandPath(Phpcs::COMMAND_NAME, './vendor/bin');
        $this->assertEquals('./vendor/bin' . DIRECTORY_SEPARATOR . Phpcs::COMMAND_NAME, $path);

        $path = $task->getCommandPath(Phpcs::COMMAND_NAME, '');
        $this->assertEquals(Phpcs::COMMAND_NAME, $path);
    }

}
