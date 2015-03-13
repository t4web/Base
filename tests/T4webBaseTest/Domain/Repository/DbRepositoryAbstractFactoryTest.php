<?php

namespace T4webBaseTest\Domain\Repository;

use T4webBase\Domain\Repository\DbRepositoryAbstractFactory;

class DbRepositoryAbstractFactoryTest extends \PHPUnit_Framework_TestCase {
    
    public function testCreateServiceWithName() {
        $moduleName = 'Foo';
        $entityName = 'Bar';
        
        $tableGatewayMock = $this->getMock('T4webBase\Db\TableGatewayInterface');
        
        $serviceLocatorMock = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface');
        $serviceLocatorMock->expects($this->at(0))
                ->method('get')
                ->with($this->equalTo("$moduleName\\$entityName\\Db\\Table"))
                ->will($this->returnValue($tableGatewayMock));
        
        $DbMapperMock = $this->getMock('T4webBase\Domain\Mapper\DbMapperInterface');
        
        $serviceLocatorMock->expects($this->at(1))
                ->method('get')
                ->with($this->equalTo("$moduleName\\$entityName\\Mapper\\DbMapper"))
                ->will($this->returnValue($DbMapperMock));
        
        $queryBuilderMock = $this->getMock('T4webBase\Db\QueryBuilderInterface');
        
        $serviceLocatorMock->expects($this->at(2))
                ->method('get')
                ->with($this->equalTo("T4webBase\\Db\\QueryBuilder"))
                ->will($this->returnValue($queryBuilderMock));
        
        
        $requestedName = "$moduleName\\$entityName\\Repository\\DbRepository";
        
        $abstractFactory = new DbRepositoryAbstractFactory();
        $repository = $abstractFactory->createServiceWithName($serviceLocatorMock, $name = 'foo', $requestedName);
        
        $this->assertInstanceOf('T4webBase\Domain\Repository\DbRepository', $repository);
        $this->assertAttributeSame($tableGatewayMock, 'tableGateway', $repository);
        $this->assertAttributeSame($DbMapperMock, 'dbMapper', $repository);
        $this->assertAttributeSame($queryBuilderMock, 'queryBuilder', $repository);
    }
    
    public function testCanCreateServiceWithName() {
        $serviceLocatorMock = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface');
        
        $abstractFactory = new DbRepositoryAbstractFactory();
        $this->assertTrue(
            $abstractFactory->canCreateServiceWithName($serviceLocatorMock, 'foo', 'Foo\Bar\Repository\DbRepository'));
        $this->assertFalse(
            $abstractFactory->canCreateServiceWithName($serviceLocatorMock, 'foo', 'Foo\Bar\Repository\DbRepository2'));
    }
    
}
