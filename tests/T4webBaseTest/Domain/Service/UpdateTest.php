<?php

namespace T4webBaseTest\Domain\Service;

use T4webBase\Domain\Collection;
use T4webBaseTest\Domain\Assets\Service\Update;
use T4webBaseTest\Domain\Assets\Criteria\Id;
use T4webBaseTest\Domain\Assets\Criteria\Ids;

class UpdateTest extends \PHPUnit_Framework_TestCase {
    
    private $service;
    private $inputFilterMock;
    private $repositoryMock;
    private $criteriaFactoryMock;
    private $entityMock;
    private $eventManagerMock;
    
    public function setUp() {
        $this->inputFilterMock = $this->getMock('T4webBase\InputFilter\InputFilterInterface');
        $this->repositoryMock = $this->getMockBuilder('T4webBase\Domain\Repository\DbRepository')
                ->disableOriginalConstructor()
                ->getMock();
        $this->criteriaFactoryMock = $this->getMockBuilder('T4webBase\Domain\Criteria\Factory')
                ->disableOriginalConstructor()
                ->getMock();
        
        $this->entityMock = $this->getMock('T4webBaseTest\Domain\Assets\EntityWithStatus');

        $this->eventManagerMock = $this->getMock('Zend\EventManager\EventManager');
        
        $this->service = new Update(
            $this->inputFilterMock,
            $this->repositoryMock,
            $this->criteriaFactoryMock,
            $this->eventManagerMock
        );
    }
    
    public function testImplementCreateInterface() {
        $this->assertInstanceOf('T4webBase\Domain\Service\UpdateInterface', $this->service);
    }
    
    public function testConstructor() {
        $this->assertAttributeSame($this->inputFilterMock, 'inputFilter', $this->service);
        $this->assertAttributeSame($this->repositoryMock, 'repository', $this->service);
    }
    
    public function testIsValid() {
        $data = array('foo' => 'bar');
        $result = true;
        
        $this->inputFilterMock->expects($this->once())
                ->method('setData')
                ->with($this->equalTo($data));
        
        $this->inputFilterMock->expects($this->once())
                ->method('isValid')
                ->will($this->returnValue($result));
        
        $actualResult = $this->service->isValid($data);
                
        $this->assertInternalType('bool', $actualResult);
        $this->assertEquals($result, $actualResult);
    }
    
    /**
     * @dataProvider providerMethodName
     */
    public function testChangeStatusNotFoundEntity($methodName) {
        $id = 111;
        
        $this->criteriaFactoryMock->expects($this->once())
                ->method('getNativeCriteria')
                ->with($this->equalTo('Id'), $this->equalTo($id))
                ->will($this->returnValue(new Id($id)));
        
        $this->repositoryMock->expects($this->once())
                ->method('find')
                ->with($this->equalTo(new Id($id)));
        
        $this->assertFalse($this->service->$methodName($id));
    }
    
    public function providerMethodName() {
        return array(
            array('activate'),
            array('inactivate'),
            array('delete'),
        );
    }
    
    /**
     * @dataProvider providerMethodNameWithMethodNameToModel
     */
    public function testChangeStatus($methodName, $modelMethodName) {
        $id = 111;
        
        $this->criteriaFactoryMock->expects($this->once())
                ->method('getNativeCriteria')
                ->with($this->equalTo('Id'), $this->equalTo($id))
                ->will($this->returnValue(new Id($id)));
        
        $this->repositoryMock->expects($this->once())
                ->method('find')
                ->with($this->equalTo(new Id($id)))
                ->will($this->returnValue($this->entityMock));
        
        $this->entityMock->expects($this->once())
                ->method($modelMethodName);
        
        $this->repositoryMock->expects($this->once())
                ->method('add')
                ->with($this->equalTo($this->entityMock));

        $this->eventManagerMock->expects($this->once())
                ->method('trigger')
                ->with($this->equalTo($methodName.':post'), $this->equalTo($this->service), $this->equalTo(array('entity' => $this->entityMock)));
        
        $this->assertTrue($this->service->$methodName($id));
    }
    
    public function providerMethodNameWithMethodNameToModel() {
        return array(
            array('activate', 'setActivated'),
            array('inactivate', 'setInactivated'),
            array('delete', 'setDeleted'),
        );
    }
    
    public function testGetMessages() {
        $result = array('foo' => 'bar');
        
        $this->inputFilterMock->expects($this->once())
                ->method('getMessages')
                ->will($this->returnValue($result));
        
        $this->assertEquals($result, $this->service->getMessages());
    }

    public function testUpdateAllReturnNull() {
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
            ->method('update');

        $result = $this->service->updateAll($id, 'Id', array());

        $this->assertFalse($result);
    }

    public function testUpdateAll() {
        $id = 1;
        $data = array();
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
            ->method('updateByAttribute')
            ->with($this->equalTo($data), $this->equalTo($id), $this->equalTo('id'));

        $result = $this->service->updateAll($id, 'Id', $data);

        $this->assertEquals($collection, $result);
    }

    public function testUpdateAllCriteriaNameDifferentToField() {
        $id = 1;
        $criteria = new Ids($id);
        $collection = new Collection();
        $collection->append($this->entityMock);
        $data = array();

        $this->criteriaFactoryMock->expects($this->once())
            ->method('getNativeCriteria')
            ->with($this->equalTo('Ids'), $this->equalTo($id))
            ->will($this->returnValue($criteria));

        $this->repositoryMock->expects($this->once())
            ->method('findMany')
            ->with($this->equalTo($criteria))
            ->will($this->returnValue($collection));

        $this->repositoryMock->expects($this->once())
            ->method('updateByAttribute')
            ->with($this->equalTo($data), $this->equalTo($id), $this->equalTo('id'));

        $result = $this->service->updateAll($id, 'Ids', $data);

        $this->assertEquals($collection, $result);
    }
}
