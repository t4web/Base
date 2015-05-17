<?php

namespace T4webBaseTest\InputFilter\Element;

use T4webBase\InputFilter\Element\InArray;

class InArrayTest extends \PHPUnit_Framework_TestCase {
    
    public function testIsValid() {
        $arrayInputFilter = new InArray('status', [1, 2, 3]);
        $arrayInputFilter->setValue('foo');

        $this->assertFalse($arrayInputFilter->isValid());
    }
    
}
