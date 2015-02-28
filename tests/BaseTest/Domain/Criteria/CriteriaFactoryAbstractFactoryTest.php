<?php

namespace BaseTest\Domain\Criteria;

use Base\Domain\Criteria\CriteriaFactoryAbstractFactory;

class DbRepositoryAbstractFactoryTest extends \PHPUnit_Framework_TestCase {
    
    public function testCreateServiceWithName() {
        $moduleName = 'Foo';
        $entityName = 'Bar';
        $dependencies = array(
            'dependencies' => array()
        );
        $criteries = array(
            'criteries' => array()
        );
        
        $moduleConfigMock = $this->getMockBuilder('Base\Module\ModuleConfig')
                ->disableOriginalConstructor()
                ->getMock();
        $moduleConfigMock->expects($this->once())
                ->method('getDbDependencies')
                ->will($this->returnValue($dependencies));
        $moduleConfigMock->expects($this->once())
                ->method('getCriteries')
                ->will($this->returnValue($criteries));
        
        $serviceLocatorMock = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface');
        $serviceLocatorMock->expects($this->at(0))
                ->method('get')
                ->with($this->equalTo("$moduleName\ModuleConfig"))
                ->will($this->returnValue($moduleConfigMock));
        
        $requestedName = "$moduleName\\$entityName\Criteria\CriteriaFactory";
        
        $abstractFactory = new CriteriaFactoryAbstractFactory();
        $criteriaFactory = $abstractFactory->createServiceWithName($serviceLocatorMock, $name = 'foo', $requestedName);
        
        $this->assertInstanceOf('Base\Domain\Criteria\Factory', $criteriaFactory);
        $this->assertAttributeSame($moduleName, 'moduleName', $criteriaFactory);
        $this->assertAttributeSame($entityName, 'entityName', $criteriaFactory);
        $this->assertAttributeSame($dependencies, 'dependencies', $criteriaFactory);
        $this->assertAttributeSame($criteries, 'criteries', $criteriaFactory);
    }
    
    public function testCanCreateServiceWithName() {
        $serviceLocatorMock = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface');
        
        $abstractFactory = new CriteriaFactoryAbstractFactory();
        $this->assertTrue(
            $abstractFactory->canCreateServiceWithName($serviceLocatorMock, 'foo', 'Foo\Bar\Criteria\CriteriaFactory'));
        $this->assertFalse(
            $abstractFactory->canCreateServiceWithName($serviceLocatorMock, 'foo', 'Foo\Bar\Criteria\CriteriaFactory2'));
    }
    
}
