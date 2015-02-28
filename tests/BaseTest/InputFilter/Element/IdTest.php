<?php

namespace BaseTest\InputFilter\Element;

use Base\InputFilter\Element\Id;

class IdTest extends \PHPUnit_Framework_TestCase {
    
    public function testIsValid() {
        $id = new Id();
        $id->setValue(0);
        
        $this->assertFalse($id->isValid());
    }
    
}
