<?php

namespace BaseTest\InputFilter\Element;

use Base\InputFilter\Element\Text;

class TextTest extends \PHPUnit_Framework_TestCase {
    
    public function testIsValid() {
        $id = new Text();
        $id->setValue('');
        
        $this->assertFalse($id->isValid());
    }
    
}
