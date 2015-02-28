<?php

namespace BaseTest\Domain\Repository;

use Base\Domain\Repository\DbRepositoryAbstractFactory;

class DbRepositoryAbstractFactoryTest extends \PHPUnit_Framework_TestCase {
    
    public function testCreateServiceWithName() {
        $moduleName = 'Foo';
        $entityName = 'Bar';
        
        $tableGatewayMock = $this->getMock('Base\Db\TableGatewayInterface');
        
        $serviceLocatorMock = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface');
        $serviceLocatorMock->expects($this->at(0))
                ->method('get')
                ->with($this->equalTo("$moduleName\\$entityName\\Db\\Table"))
                ->will($this->returnValue($tableGatewayMock));
        
        $DbMapperMock = $this->getMock('Base\Domain\Mapper\DbMapperInterface');
        
        $serviceLocatorMock->expects($this->at(1))
                ->method('get')
                ->with($this->equalTo("$moduleName\\$entityName\\Mapper\\DbMapper"))
                ->will($this->returnValue($DbMapperMock));
        
        $queryBuilderMock = $this->getMock('Base\Db\QueryBuilderInterface');
        
        $serviceLocatorMock->expects($this->at(2))
                ->method('get')
                ->with($this->equalTo("Base\\Db\\QueryBuilder"))
                ->will($this->returnValue($queryBuilderMock));
        
        
        $requestedName = "$moduleName\\$entityName\\Repository\\DbRepository";
        
        $abstractFactory = new DbRepositoryAbstractFactory();
        $repository = $abstractFactory->createServiceWithName($serviceLocatorMock, $name = 'foo', $requestedName);
        
        $this->assertInstanceOf('Base\Domain\Repository\DbRepository', $repository);
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
