<?php

namespace T4webBaseTest\Domain\Repository;

use T4webBase\Domain\Repository\IdentityMap;

class IdentityMapTest extends \PHPUnit_Framework_TestCase {
    
    public function testInstanseOfArrayObject() {
        $this->assertInstanceOf('ArrayObject', new IdentityMap());
    }
    
}
