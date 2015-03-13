<?php

namespace T4webBaseTest\InputFilter\Element;

use T4webBase\InputFilter\Element\Int;

class IntTest extends \PHPUnit_Framework_TestCase {
    
    public function testIsValid() {
        $int = new Int();
        $int->setValue(0);
        
        $this->assertTrue($int->isValid());
    }
    
}
