<?php

namespace BaseTest\InputFilter\Element;

use Base\InputFilter\Element\Name;

class NameTest extends \PHPUnit_Framework_TestCase {
    
    public function testIsValid() {
        $id = new Name();
        $id->setValue('');

        $this->assertFalse($id->isValid());
    }
    
}
