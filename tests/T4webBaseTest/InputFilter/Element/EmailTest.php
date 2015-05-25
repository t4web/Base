<?php

namespace T4webBaseTest\InputFilter\Element;

use T4webBase\InputFilter\Element\Email;

class EmailTest extends \PHPUnit_Framework_TestCase {
    
    public function testIsValid() {
        $emailInputFilter = new Email();
        $emailInputFilter->setValue('foo');

        $this->assertFalse($emailInputFilter->isValid());
    }
    
}
