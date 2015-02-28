<?php

namespace Base\Tests\Domain\Service;

use Base\Domain\Service\Create;

class CreateTest extends \PHPUnit_Framework_TestCase {
    
    private $createService;
    private $createServiceWithoutEventManager;
    private $inputFilterMock;
    private $repositoryMock;
    private $entityFactoryMock;
    private $eventManagerMock;
    
    public function setUp() {
        $this->inputFilterMock = $this->getMock('Base\InputFilter\InputFilterInterface');
        $this->repositoryMock = $this->getMockBuilder('Base\Domain\Repository\DbRepository')
                ->disableOriginalConstructor()
                ->getMock();
        $this->entityFactoryMock = $this->getMock('Base\Domain\Factory\EntityFactoryInterface');
        $this->eventManagerMock = $this->getMock('Zend\EventManager\EventManager');

        $this->createService = new Create(
            $this->inputFilterMock,
            $this->repositoryMock,
            $this->entityFactoryMock,
            $this->eventManagerMock
        );

        $this->createServiceWithoutEventManager = new Create(
            $this->inputFilterMock,
            $this->repositoryMock,
            $this->entityFactoryMock
        );
    }
    
    public function testImplementCreateInterface() {
        $this->assertInstanceOf('Base\Domain\Service\CreateInterface', $this->createService);
    }
    
    public function testConstructor() {
        $this->assertAttributeSame($this->inputFilterMock, 'inputFilter', $this->createService);
        $this->assertAttributeSame($this->repositoryMock, 'repository', $this->createService);
        $this->assertAttributeSame($this->entityFactoryMock, 'entityFactory', $this->createService);
        $this->assertAttributeSame($this->eventManagerMock, 'eventManager', $this->createService);
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
        
        $actualResult = $this->createService->isValid($data);
                
        $this->assertInternalType('bool', $actualResult);
        $this->assertEquals($result, $actualResult);
    }
    
    public function testCreate() {
        $data = array('foo' => 'bar');
        $id = 111;
        
        $entityMock = $this->getMock('Base\Domain\Entity');
        
        $this->inputFilterMock->expects($this->once())
                ->method('getValues')
                ->will($this->returnValue($data));
        
        $this->entityFactoryMock->expects($this->once())
                ->method('create')
                ->with($this->equalTo($data), $this->equalTo($id))
                ->will($this->returnValue($entityMock));
        
        $this->repositoryMock->expects($this->once())
                ->method('add')
                ->with($this->identicalTo($entityMock));

        $this->eventManagerMock->expects($this->once())
            ->method('trigger');

        $this->assertSame($entityMock, $this->createService->create($id));
    }
    
    public function testCreateWithId() {
        $data = array('foo' => 'bar');
        
        $entityMock = $this->getMock('Base\Domain\Entity');
        
        $this->inputFilterMock->expects($this->once())
                ->method('getValues')
                ->will($this->returnValue($data));
        
        $this->entityFactoryMock->expects($this->once())
                ->method('create')
                ->with($this->equalTo($data))
                ->will($this->returnValue($entityMock));
        
        $this->repositoryMock->expects($this->once())
                ->method('add')
                ->with($this->identicalTo($entityMock));

        $this->eventManagerMock->expects($this->never())
            ->method('trigger');

        $this->assertSame($entityMock, $this->createServiceWithoutEventManager->create());
    }
    
    public function testCreateInputFilterGetValuesReturnEmptyArray() {
        $this->inputFilterMock->expects($this->once())
                ->method('getValues')
                ->will($this->returnValue(array()));
        
        $this->setExpectedException('RuntimeException', "Cannot create entity form empty data");
        
        $this->createService->create();
    }
    
    public function testGetMessages() {
        $result = array('foo' => 'bar');
        
        $this->inputFilterMock->expects($this->once())
                ->method('getMessages')
                ->will($this->returnValue($result));
        
        $this->assertEquals($result, $this->createService->getMessages());
    }
}
