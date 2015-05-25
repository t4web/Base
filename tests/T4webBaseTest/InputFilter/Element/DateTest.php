<?php

namespace T4webBaseTest\InputFilter\Element;

use T4webBase\InputFilter\Element\Date;

class DateTest extends \PHPUnit_Framework_TestCase {
    
    public function testIsValid() {
        $dateInputFilter = new Date();
        $dateInputFilter->setValue('2015');

        $this->assertFalse($dateInputFilter->isValid());
    }
    
}
