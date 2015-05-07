<?php

namespace T4webBaseTest\Domain\Service;

use T4webBase\Domain\Service\Delete;
use T4webBase\Domain\Collection;
use T4webBase\InputFilter\InvalidInputError;
use T4webBaseTest\Domain\Assets\Criteria\Id;
use T4webBaseTest\Domain\Assets\Criteria\Ids;

class DeleteTest extends \PHPUnit_Framework_TestCase {
    
    private $service;
    private $repositoryMock;
    private $criteriaFactoryMock;
    private $entityMock;
    private $eventManagerMock;
    
    public function setUp() {
        $this->repositoryMock = $this->getMockBuilder('T4webBase\Domain\Repository\DbRepository')->disableOriginalConstructor()->getMock();
        $this->criteriaFactoryMock = $this->getMockBuilder('T4webBase\Domain\Criteria\Factory')->disableOriginalConstructor()->getMock();
        $this->eventManagerMock = $this->getMockBuilder('Zend\EventManager\EventManager')->disableOriginalConstructor()->getMock();

        $this->entityMock = $this->getMock('T4webBaseTest\Domain\Assets\EntityWithStatus');
        
        $this->service = new Delete(
            $this->repositoryMock,
            $this->criteriaFactoryMock,
            $this->eventManagerMock
        );
    }
    
    public function testImplementCreateInterface() {
        $this->assertInstanceOf('T4webBase\Domain\Service\DeleteInterface', $this->service);
    }
    
    public function testDelete_Normal_Delete() {
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
            ->method('trigger')
            ->with($this->equalTo('delete:post'), $this->equalTo($this->service), $this->equalTo(array('entity' => $this->entityMock)));
        
        $result = $this->service->delete($id);
        
        $this->assertSame($this->entityMock, $result);
    }
    
    public function testDelete_ByAttribute_Delete() {
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
            ->method('trigger')
            ->with($this->equalTo('delete:post'), $this->equalTo($this->service), $this->equalTo(array('entity' => $this->entityMock)));

        $result = $this->service->delete($id, 'Ids');
        
        $this->assertSame($this->entityMock, $result);
    }
    
    public function testDelete_ErrorNotFoundEntity_ReturnNull() {
        $id = 1;
        $criteria = new Id($id);
        $errors = new InvalidInputError(array('general' => 'Entity does not found.'));

        $this->criteriaFactoryMock->expects($this->once())
                ->method('getNativeCriteria')
                ->with($this->equalTo('Id'), $this->equalTo($id))
                ->will($this->returnValue($criteria));
        
        $this->repositoryMock->expects($this->once())
            ->method('find')
            ->with($this->equalTo($criteria));
        
        $this->repositoryMock->expects($this->never())
            ->method('delete');

        $this->eventManagerMock->expects($this->never())
            ->method('trigger');
        
        $result = $this->service->delete($id);
        
        $this->assertFalse($result);
        $this->assertEquals($errors, $this->service->getErrors());
    }
    
    public function testDeleteAll_EntitiesNotFound_ReturnNull() {
        $id = 1;
        $criteria = new Id($id);
        $collection = new Collection();
        $errors = new InvalidInputError(array('general' => 'Entities does not found.'));

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

        $this->eventManagerMock->expects($this->never())
            ->method('trigger');
        
        $result = $this->service->deleteAll($id);
        
        $this->assertFalse($result);
        $this->assertEquals($errors, $this->service->getErrors());
    }
    
    public function testDeleteAll_Normal_Delete() {
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
            ->method('trigger')
            ->with($this->equalTo('deleteAll:post'), $this->equalTo($this->service), $this->equalTo(array('collection' => $collection)));

        $result = $this->service->deleteAll($id);
        
        $this->assertEquals($collection, $result);
    }

    public function testDeleteAll_CriteriaNameDifferentToField_Delete() {
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
            ->method('trigger')
            ->with($this->equalTo('deleteAll:post'), $this->equalTo($this->service), $this->equalTo(array('collection' => $collection)));

        $result = $this->service->deleteAll($id, 'Ids');

        $this->assertEquals($collection, $result);
    }
}
