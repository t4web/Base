<?php

namespace BaseTest\Domain\Repository;

use Base\Domain\Repository\IdentityMap;

class IdentityMapTest extends \PHPUnit_Framework_TestCase {
    
    public function testInstanseOfArrayObject() {
        $this->assertInstanceOf('ArrayObject', new IdentityMap());
    }
    
}
