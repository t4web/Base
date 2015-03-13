<?php

namespace T4webBaseTest\Domain;

use T4webBaseTest\Domain\Assets\EntityStatus1;
use T4webBaseTest\Domain\Assets\EntityStatus2;

class StatusTest extends \PHPUnit_Framework_TestCase {
    
    public function testEntityStatus1() {
        $status = EntityStatus1::create(EntityStatus1::ACTIVE);
        
        $this->assertInstanceOf('T4webBaseTest\Domain\Assets\EntityStatus1', $status);
    }
    
    public function testEntityStatus1Broken() {
        $status = EntityStatus2::create(EntityStatus2::ACTIVE);
        
        $this->assertInstanceOf('T4webBaseTest\Domain\Assets\EntityStatus2', $status);
    }
    
}
