<?php

namespace BaseTest\Domain\Service;

use Base\Domain\Service\Delete;
use Base\Domain\Collection;
use BaseTest\Domain\Assets\Criteria\Id;
use BaseTest\Domain\Assets\Criteria\Ids;

class DeleteTest extends \PHPUnit_Framework_TestCase {
    
    private $service;
    private $repositoryMock;
    private $criteriaFactoryMock;
    private $entityMock;
    private $eventManagerMock;
    
    public function setUp() {
        $this->repositoryMock = $this->getMockBuilder('Base\Domain\Repository\DbRepository')
                ->disableOriginalConstructor()
                ->getMock();
        $this->criteriaFactoryMock = $this->getMockBuilder('Base\Domain\Criteria\Factory')
                ->disableOriginalConstructor()
                ->getMock();
        $this->eventManagerMock = $this->getMockBuilder('Zend\EventManager\EventManager')
                ->disableOriginalConstructor()
                ->getMock();

        $this->entityMock = $this->getMock('BaseTest\Domain\Assets\EntityWithStatus');
        
        $this->service = new Delete(
            $this->repositoryMock,
            $this->criteriaFactoryMock,
            $this->eventManagerMock
        );
    }
    
    public function testImplementCreateInterface() {
        $this->assertInstanceOf('Base\Domain\Service\DeleteInterface', $this->service);
    }
    
    public function testDelete() {
        $id = 1;
        $criteria = new Id($id);
        
        $this->criteriaFactoryMock->expects($this->once())
                ->method('getNativeCriteria')
                ->with($this->equalTo('Id'), $this->equalTo($id))
                ->will($this->returnValue($criteria));
        
        $this->repositoryMock->expects($this->once())
            ->method('find')
            ->with($this->equalTo($criteria))
            ->will($this->returnValue($this->entityMock));
        
        $this->repositoryMock->expects($this->once())
            ->method('delete')
            ->with($this->equalTo($this->entityMock));

        $this->eventManagerMock->expects($this->once())
            ->method('trigger');
        
        $result = $this->service->delete($id);
        
        $this->assertSame($this->entityMock, $result);
    }
    
    public function testDeleteByAttribyte() {
        $id = 1;
        $criteria = new Ids($id);
        
        $this->criteriaFactoryMock->expects($this->once())
                ->method('getNativeCriteria')
                ->with($this->equalTo('Ids'), $this->equalTo($id))
                ->will($this->returnValue($criteria));
        
        $this->repositoryMock->expects($this->once())
            ->method('find')
            ->with($this->equalTo($criteria))
            ->will($this->returnValue($this->entityMock));
        
        $this->repositoryMock->expects($this->once())
            ->method('delete')
            ->with($this->equalTo($this->entityMock));

        $this->eventManagerMock->expects($this->once())
            ->method('trigger');

        $result = $this->service->delete($id, 'Ids');
        
        $this->assertSame($this->entityMock, $result);
    }
    
    public function testDeleteReturnNull() {
        $id = 1;
        $criteria = new Id($id);
        
        $this->criteriaFactoryMock->expects($this->once())
                ->method('getNativeCriteria')
                ->with($this->equalTo('Id'), $this->equalTo($id))
                ->will($this->returnValue($criteria));
        
        $this->repositoryMock->expects($this->once())
            ->method('find')
            ->with($this->equalTo($criteria));
        
        $this->repositoryMock->expects($this->never())
            ->method('delete');
        
        $result = $this->service->delete($id);
        
        $this->assertFalse($result);
    }
    
    public function testDeleteAllReturnNull() {
        $id = 1;
        $criteria = new Id($id);
        $collection = new Collection();
        
        $this->criteriaFactoryMock->expects($this->once())
            ->method('getNativeCriteria')
            ->with($this->equalTo('Id'), $this->equalTo($id))
            ->will($this->returnValue($criteria));
        
        $this->repositoryMock->expects($this->once())
            ->method('findMany')
            ->with($this->equalTo($criteria))
            ->will($this->returnValue($collection));
        
        $this->repositoryMock->expects($this->never())
            ->method('delete');
        
        $result = $this->service->deleteAll($id);
        
        $this->assertFalse($result);
    }
    
    public function testDeleteAll() {
        $id = 1;
        $criteria = new Id($id);
        $collection = new Collection();
        $collection->append($this->entityMock);
        
        $this->criteriaFactoryMock->expects($this->once())
            ->method('getNativeCriteria')
            ->with($this->equalTo('Id'), $this->equalTo($id))
            ->will($this->returnValue($criteria));
        
        $this->repositoryMock->expects($this->once())
            ->method('findMany')
            ->with($this->equalTo($criteria))
            ->will($this->returnValue($collection));
        
        $this->repositoryMock->expects($this->once())
            ->method('deleteByAttribute')
            ->with($this->equalTo($id), $this->equalTo('id'));

        $this->eventManagerMock->expects($this->once())
            ->method('trigger');

        $result = $this->service->deleteAll($id);
        
        $this->assertEquals($collection, $result);
    }

    public function testDeleteAllCriteriaNameDifferentToField() {
        $id = 1;
        $criteria = new Ids($id);
        $collection = new Collection();
        $collection->append($this->entityMock);

        $this->criteriaFactoryMock->expects($this->once())
            ->method('getNativeCriteria')
            ->with($this->equalTo('Ids'), $this->equalTo($id))
            ->will($this->returnValue($criteria));

        $this->repositoryMock->expects($this->once())
            ->method('findMany')
            ->with($this->equalTo($criteria))
            ->will($this->returnValue($collection));

        $this->repositoryMock->expects($this->once())
            ->method('deleteByAttribute')
            ->with($this->equalTo($id), $this->equalTo('id'));

        $this->eventManagerMock->expects($this->once())
            ->method('trigger');

        $result = $this->service->deleteAll($id, 'Ids');

        $this->assertEquals($collection, $result);
    }
}
