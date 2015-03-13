<?php

namespace T4webBaseTest\InputFilter\Element;

use T4webBase\InputFilter\Element\Name;

class NameTest extends \PHPUnit_Framework_TestCase {
    
    public function testIsValid() {
        $id = new Name();
        $id->setValue('');

        $this->assertFalse($id->isValid());
    }
    
}
