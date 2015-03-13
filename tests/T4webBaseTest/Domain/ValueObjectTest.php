<?php

namespace T4webBaseTest\Domain;

use T4webBase\Domain\ValueObject;

class ValueObjectTest extends \PHPUnit_Framework_TestCase {
    
    public function testCanCreate() {
        $valueObject = new ValueObject();
        $this->assertInstanceOf('T4webBase\Object\HydratingObject', $valueObject);
    }
    
}
