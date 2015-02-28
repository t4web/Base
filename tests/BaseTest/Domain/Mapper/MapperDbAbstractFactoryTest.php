<?php

namespace BaseTest\Domain\Mapper;

use Base\Domain\Mapper\DbMapperAbstractFactory;

class DbMapperAbstractFactoryTest extends \PHPUnit_Framework_TestCase {
    
    public function testCreateServiceWithName() {
        $moduleName = 'Foo';
        $entityName = 'Bar';
        $columnsAsAttributesMap = array(
            'bar' => 'baz'
        );
        
        $moduleConfigMock = $this->getMockBuilder('Base\Module\ModuleConfig')
                ->disableOriginalConstructor()
                ->getMock();
        $moduleConfigMock->expects($this->once())
                ->method('getDbTableColumnsAsAttributesMap')
                ->with($this->equalTo(strtolower("$moduleName-$entityName")))
                ->will($this->returnValue($columnsAsAttributesMap));
        
        $serviceLocatorMock = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface');
        $serviceLocatorMock->expects($this->at(0))
                ->method('get')
                ->with($this->equalTo("$moduleName\\ModuleConfig"))
                ->will($this->returnValue($moduleConfigMock));
        
        $entityFactoryMock = $this->getMock('Base\Domain\Factory\EntityFactoryInterface');
        $serviceLocatorMock->expects($this->at(1))
                ->method('get')
                ->with($this->equalTo("$moduleName\\$entityName\\Factory\\EntityFactory"))
                ->will($this->returnValue($entityFactoryMock));
        
        $requestedName = "$moduleName\\$entityName\\Mapper\\DbMapper";
        
        $abstractFactory = new DbMapperAbstractFactory();
        $mapper = $abstractFactory->createServiceWithName($serviceLocatorMock, $name = 'foo', $requestedName);
        
        $this->assertInstanceOf('Base\Domain\Mapper\DbMapper', $mapper);
        $this->assertAttributeSame($columnsAsAttributesMap, 'columnsAsAttributesMap', $mapper);
        $this->assertAttributeSame($entityFactoryMock, 'factory', $mapper);
    }
    
    public function testCanCreateServiceWithName() {
        $serviceLocatorMock = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface');
        
        $abstractFactory = new DbMapperAbstractFactory();
        $this->assertTrue(
            $abstractFactory->canCreateServiceWithName($serviceLocatorMock, 'foo', 'Foo\Bar\Mapper\DbMapper'));
        $this->assertFalse(
            $abstractFactory->canCreateServiceWithName($serviceLocatorMock, 'foo', 'Foo\Bar\Mapper\DbMapper2'));
    }
    
}
