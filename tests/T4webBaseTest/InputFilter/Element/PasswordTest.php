<?php

namespace T4webBaseTest\InputFilter\Element;

use T4webBase\InputFilter\Element\Password;

class PasswordTest extends \PHPUnit_Framework_TestCase {
    
    public function testIsValid() {
        $passwordInputFilter = new Password();
        $passwordInputFilter->setValue('');

        $this->assertFalse($passwordInputFilter->isValid());
    }
    
}
