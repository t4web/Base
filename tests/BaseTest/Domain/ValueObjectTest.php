<?php

namespace BaseTest\Domain;

use Base\Domain\ValueObject;

class ValueObjectTest extends \PHPUnit_Framework_TestCase {
    
    public function testCanCreate() {
        $valueObject = new ValueObject();
        $this->assertInstanceOf('Base\Object\HydratingObject', $valueObject);
    }
    
}
