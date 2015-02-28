<?php

namespace BaseTest\Domain\Criteria;

use Base\Domain\Criteria\CompositeCriteria;

class CompositeCriteriaTest extends \PHPUnit_Framework_TestCase {
    
    public function testImplementsCriteriaInterface() {
        $this->assertInstanceOf('Base\Domain\Criteria\CriteriaInterface', new CompositeCriteria());
    }
    
    public function testAdd() {
        $criteriaMock = $this->getMock('Base\Domain\Criteria\CriteriaInterface');
        
        $compositeCriteria = new CompositeCriteria();
        $compositeCriteria->add($criteriaMock);
        
        $this->assertAttributeSame(array($criteriaMock), 'children', $compositeCriteria);
    }
    
    public function testBuild() {
        
        $queryBuilderMock = $this->getMockBuilder('Base\Db\QueryBuilder')
                ->disableOriginalConstructor()
                ->getMock();
        
        $criteria1Mock = $this->getMock('Base\Domain\Criteria\CriteriaInterface');
        $criteria1Mock->expects($this->once())
                ->method('build')
                ->with($this->identicalTo($queryBuilderMock));
        
        $criteria2Mock = $this->getMock('Base\Domain\Criteria\CriteriaInterface');
        $criteria2Mock->expects($this->once())
                ->method('build')
                ->with($this->identicalTo($queryBuilderMock));
        
        $compositeCriteria = new CompositeCriteria();
        $compositeCriteria->add($criteria1Mock);
        $compositeCriteria->add($criteria2Mock);
        
        $compositeCriteria->build($queryBuilderMock);
    }
    
}