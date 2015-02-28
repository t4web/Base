<?php

namespace BaseTest\InputFilter;

use Base\InputFilter\InputFilter;

class InputFilterTest extends \PHPUnit_Framework_TestCase {
    
    public function testImplementInputFilterInterface() {
        $inputFilter = new InputFilter();
        
        $this->assertInstanceOf('Base\InputFilter\InputFilterInterface', $inputFilter);
    }
    
}
