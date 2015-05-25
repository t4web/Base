<?php

namespace T4webBaseTest\InputFilter\Element;

use T4webBase\InputFilter\Element\LocalhostDomain;

class LocalhostDomainTest extends \PHPUnit_Framework_TestCase {
    
    public function testIsValid() {
        $hostInputFilter = new LocalhostDomain();
        $hostInputFilter->setValue('foo#com');

        $this->assertFalse($hostInputFilter->isValid());
    }
    
}
