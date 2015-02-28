<?php

namespace BaseTest\Domain\Service;

use Base\Domain\Service\BaseFinder;
use Base\Domain\Collection;

class BaseFinderTest extends \PHPUnit_Framework_TestCase {
   
    protected $criteriaFactoryMock;
    protected $repositoryMock;
    protected $service;
    
    public function setUp() {
        $this->criteriaFactoryMock = $this->getMockBuilder('Base\Domain\Criteria\Factory')
            ->disableOriginalConstructor()
            ->getMock();
        
        $this->repositoryMock = $this->getMockBuilder('Base\Domain\Repository\DbRepository')
            ->disableOriginalConstructor()
            ->getMock();
        
        $this->service = new BaseFinder($this->repositoryMock, $this->criteriaFactoryMock);
    }
    
    public function testFindMany() {
        $filter = array();
        $expectedCollection = new Collection();
        
        $criteriaMock = $this->getMock('Base\Domain\Criteria\CompositeCriteria');
        $this->criteriaFactoryMock->expects($this->once())
                ->method('create')
                ->with($this->equalTo($filter))
                ->will($this->returnValue($criteriaMock));
        
        $this->repositoryMock->expects($this->once())
                ->method('findMany')
                ->with($this->identicalTo($criteriaMock))
                ->will($this->returnValue($expectedCollection));
        
        $actualCollection = $this->service->findMany($filter);
        
        $this->assertSame($expectedCollection, $actualCollection);
    }
    
    public function testCount() {
        $filter = array();
        $expected = 1;
        
        $criteriaMock = $this->getMock('Base\Domain\Criteria\CompositeCriteria');
        $this->criteriaFactoryMock->expects($this->once())
                ->method('create')
                ->with($this->equalTo($filter))
                ->will($this->returnValue($criteriaMock));
        
        $this->repositoryMock->expects($this->once())
                ->method('count')
                ->with($this->identicalTo($criteriaMock))
                ->will($this->returnValue($expected));
        
        $actual = $this->service->count($filter);
        
        $this->assertSame($expected, $actual);
    }

    public function testFindManyByColumns() {
        $filter = array();
        $expectedArray = array();
        $columns = array('id');

        $criteriaMock = $this->getMock('Base\Domain\Criteria\CompositeCriteria');
        $this->criteriaFactoryMock->expects($this->once())
            ->method('create')
            ->with($this->equalTo($filter))
            ->will($this->returnValue($criteriaMock));

        $this->repositoryMock->expects($this->once())
            ->method('findManyByColumns')
            ->with($this->identicalTo($criteriaMock), $this->equalTo($columns))
            ->will($this->returnValue($expectedArray));

        $actualArray = $this->service->findManyByColumns($filter, $columns);

        $this->assertEquals($expectedArray, $actualArray);
    }
}
