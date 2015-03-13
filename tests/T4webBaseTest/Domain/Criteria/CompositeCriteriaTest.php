<?php

namespace T4webBaseTest\Domain\Criteria;

use T4webBase\Domain\Criteria\CompositeCriteria;

class CompositeCriteriaTest extends \PHPUnit_Framework_TestCase {
    
    public function testImplementsCriteriaInterface() {
        $this->assertInstanceOf('T4webBase\Domain\Criteria\CriteriaInterface', new CompositeCriteria());
    }
    
    public function testAdd() {
        $criteriaMock = $this->getMock('T4webBase\Domain\Criteria\CriteriaInterface');
        
        $compositeCriteria = new CompositeCriteria();
        $compositeCriteria->add($criteriaMock);
        
        $this->assertAttributeSame(array($criteriaMock), 'children', $compositeCriteria);
    }
    
    public function testBuild() {
        
        $queryBuilderMock = $this->getMockBuilder('T4webBase\Db\QueryBuilder')
                ->disableOriginalConstructor()
                ->getMock();
        
        $criteria1Mock = $this->getMock('T4webBase\Domain\Criteria\CriteriaInterface');
        $criteria1Mock->expects($this->once())
                ->method('build')
                ->with($this->identicalTo($queryBuilderMock));
        
        $criteria2Mock = $this->getMock('T4webBase\Domain\Criteria\CriteriaInterface');
        $criteria2Mock->expects($this->once())
                ->method('build')
                ->with($this->identicalTo($queryBuilderMock));
        
        $compositeCriteria = new CompositeCriteria();
        $compositeCriteria->add($criteria1Mock);
        $compositeCriteria->add($criteria2Mock);
        
        $compositeCriteria->build($queryBuilderMock);
    }
    
}