<?php

namespace spec;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class QualityCheckerTaskPhpunitSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('QualityCheckerTaskPhpunit');
    }
}
