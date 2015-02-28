<?php

namespace BaseTest\Domain;

use Base\Domain\HydratingEntity;

class HydratingEntityTest extends \PHPUnit_Framework_TestCase {
    
    public function testHydratingEntityExtendsT4ObjectHydratingObject() {
        $this->assertInstanceOf('Base\Object\HydratingObject', new HydratingEntity());
    }
    
}
