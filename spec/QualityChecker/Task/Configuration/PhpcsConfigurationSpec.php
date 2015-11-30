<?php

namespace spec\QualityChecker\Task\Configuration;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class PhpcsConfigurationSpec, this is only an example spec file to run
 * phpspec with quality checker. All tests for this project are in unit directory.
 *
 * @package spec\QualityChecker\Task\Configuration
 */
class PhpcsConfigurationSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('QualityChecker\Task\Configuration\PhpcsConfiguration');
    }
}
