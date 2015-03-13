<?php

namespace T4webBaseTest\InputFilter;

use T4webBase\InputFilter\InputFilter;

class InputFilterTest extends \PHPUnit_Framework_TestCase {
    
    public function testImplementInputFilterInterface() {
        $inputFilter = new InputFilter();
        
        $this->assertInstanceOf('T4webBase\InputFilter\InputFilterInterface', $inputFilter);
    }
    
}
