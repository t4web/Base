<?php

namespace T4webBaseTest\Db;

class SelectTest extends \PHPUnit_Framework_TestCase {
    
    public function testInstanseOfZendDbSelect() {
        $selectMock = $this->getMockBuilder('T4webBase\Db\Select')
                ->disableOriginalConstructor()
                ->getMock();
        
        $this->assertInstanceOf('T4webBase\Db\SelectInterface', $selectMock);
    }
    
}
