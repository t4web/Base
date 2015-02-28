<?php

namespace BaseTest\Domain\Source;

use Base\Db\TableAbstractFactory;

class TableGatewayAbstractFactoryTest extends \PHPUnit_Framework_TestCase {
    
    public function testCreateServiceWithName() {
        $moduleName = 'Foo';
        $entityName = 'Bar';

        $moduleConfigMock = $this->getMockBuilder('Base\Module\ModuleConfig')
                ->disableOriginalConstructor()
                ->getMock();
        
        $moduleConfigMock->expects($this->once())
                ->method('getDbTablePrimaryKey')
                ->with($this->equalTo(strtolower("$moduleName-$entityName")))
                ->will($this->returnValue('id'));
        
        $tableGatewayMock = $this->getMockBuilder('Base\Db\TableGateway')
                ->disableOriginalConstructor()
                ->getMock();
        
        $serviceLocatorMock = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface');
        $serviceLocatorMock->expects($this->at(0))
                ->method('get')
                ->with($this->equalTo("$moduleName\\ModuleConfig"))
                ->will($this->returnValue($moduleConfigMock));
        
        $serviceLocatorMock->expects($this->at(1))
                ->method('get')
                ->with($this->equalTo("$moduleName\\{$entityName}\\Db\\TableGateway"))
                ->will($this->returnValue($tableGatewayMock));
        
        $requestedName = "$moduleName\\$entityName\\Db\\Table";
        
        $abstractFactory = new TableAbstractFactory();
        $tableGateway = $abstractFactory->createServiceWithName($serviceLocatorMock, $name = 'foo', $requestedName);
        
        $this->assertInstanceOf('Base\Db\Table', $tableGateway);
        $this->assertAttributeSame($tableGatewayMock, 'tableGateway', $tableGateway);
        $this->assertAttributeSame('id', 'primaryKey', $tableGateway);
    }
    
    public function testCanCreateServiceWithName() {
        $serviceLocatorMock = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface');
        
        $abstractFactory = new TableAbstractFactory();
        $this->assertTrue(
            $abstractFactory->canCreateServiceWithName($serviceLocatorMock, 'foo', 'Foo\Bar\Db\Table'));
        $this->assertFalse(
            $abstractFactory->canCreateServiceWithName($serviceLocatorMock, 'foo', 'Foo\Bar\Db\Table2'));
    }
    
}
