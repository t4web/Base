<?php

namespace T4webBaseTest\InputFilter\Element;

use T4webBase\InputFilter\Element\ArrayInArray;

class ArrayInArrayTest extends \PHPUnit_Framework_TestCase {
    
    public function testIsValid() {
        $arrayInputFilter = new ArrayInArray('statuses', ['active', 'inactive', 'deleted']);
        $arrayInputFilter->setValue([1, 2, 3]);

        $this->assertFalse($arrayInputFilter->isValid());
    }
    
}
