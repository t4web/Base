<?php

namespace BaseTest\Domain\Factory;

use Base\Domain\Factory\EntityAbstractFactory;

class EntityAbstractFactoryTest extends \PHPUnit_Framework_TestCase {
    
    public function testCreateServiceWithName() {
        $moduleName = 'Foo';
        $entityName = 'Bar';
        
        $serviceLocatorMock = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface');
        
        $requestedName = "$moduleName\\$entityName\\Factory\\EntityFactory";
        
        $abstractFactory = new EntityAbstractFactory();
        $entityFactory = $abstractFactory->createServiceWithName($serviceLocatorMock, $name = 'foo', $requestedName);
        
        $this->assertInstanceOf('Base\Domain\Factory\EntityFactory', $entityFactory);
        $this->assertAttributeEquals("$moduleName\\$entityName\\$entityName", 'entityClass', $entityFactory);
        $this->assertAttributeEquals('Base\Domain\Collection', 'collectionClass', $entityFactory);
    }
    
    public function testCanCreateServiceWithName() {
        $serviceLocatorMock = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface');
        
        $abstractFactory = new EntityAbstractFactory();
        $this->assertTrue(
            $abstractFactory->canCreateServiceWithName($serviceLocatorMock, 'foo', 'Foo\Bar\Factory\EntityFactory'));
        $this->assertFalse(
            $abstractFactory->canCreateServiceWithName($serviceLocatorMock, 'foo', 'Foo\Bar\Factory\EntityFactory2'));
    }
    
}
