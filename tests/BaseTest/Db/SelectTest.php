<?php

namespace BaseTest\Db;

class SelectTest extends \PHPUnit_Framework_TestCase {
    
    public function testInstanseOfZendDbSelect() {
        $selectMock = $this->getMockBuilder('Base\Db\Select')
                ->disableOriginalConstructor()
                ->getMock();
        
        $this->assertInstanceOf('Base\Db\SelectInterface', $selectMock);
    }
    
}
