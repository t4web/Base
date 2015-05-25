<?php

namespace T4webBaseTest\InputFilter\Element;

use T4webBase\InputFilter\Element\Ip;

class IpTest extends \PHPUnit_Framework_TestCase {
    
    public function testIsValid() {
        $ipInputFilter = new Ip();
        $ipInputFilter->setValue('foo');

        $this->assertFalse($ipInputFilter->isValid());
    }
    
}
