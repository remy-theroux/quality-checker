<?php

namespace spec\QualityChecker\Task\Configuration;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PhpcsConfigurationSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('QualityChecker\Task\Configuration\PhpcsConfiguration');
    }
}
