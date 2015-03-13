<?php

namespace T4webBaseTest\InputFilter\Element;

use T4webBase\InputFilter\Element\Text;

class TextTest extends \PHPUnit_Framework_TestCase {
    
    public function testIsValid() {
        $id = new Text();
        $id->setValue('');
        
        $this->assertFalse($id->isValid());
    }
    
}
