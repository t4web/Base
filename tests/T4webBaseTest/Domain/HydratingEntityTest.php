<?php

namespace T4webBaseTest\Domain;

use T4webBase\Domain\HydratingEntity;

class HydratingEntityTest extends \PHPUnit_Framework_TestCase {
    
    public function testHydratingEntityExtendsT4ObjectHydratingObject() {
        $this->assertInstanceOf('T4webBase\Object\HydratingObject', new HydratingEntity());
    }
    
}
