<?php

namespace spec;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class QualityCheckerTaskPhpuintSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('QualityCheckerTaskPhpuint');
    }
}
