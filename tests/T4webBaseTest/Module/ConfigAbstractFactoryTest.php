<?php

namespace T4webBaseTest\Module;

use T4webBase\Module\ConfigAbstractFactory;

class ConfigAbstractFactoryTest extends \PHPUnit_Framework_TestCase {

    public function testCanCreateServiceWithName() {
        $serviceLocatorMock = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface');

        $abstractFactory = new ConfigAbstractFactory();
        $this->assertTrue(
            $abstractFactory->canCreateServiceWithName($serviceLocatorMock, 'foo', 'ModuleName\ModuleConfig'));
        $this->assertFalse(
            $abstractFactory->canCreateServiceWithName($serviceLocatorMock, 'foo', 'ModuleName\ModuleConfigX'));
    }

    public function testCreateServiceWithName() {
        $someConfig = ['key' => 'value'];
        $moduleName = 'Foo';
        
        $serviceLocatorMock = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface');

        $moduleManagerMock = $this->getMockBuilder('Zend\ModuleManager\ModuleManager')
            ->disableOriginalConstructor()
            ->getMock();

        $serviceLocatorMock->expects($this->at(0))
                ->method('get')
                ->with($this->equalTo("ModuleManager"))
                ->will($this->returnValue($moduleManagerMock));

        $moduleMock = $this->getMock('Zend\ModuleManager\Feature\ConfigProviderInterface');

        $moduleManagerMock->expects($this->once())
                ->method('getModule')
                ->with($this->equalTo($moduleName))
                ->will($this->returnValue($moduleMock));

        $moduleMock->expects($this->once())
                ->method('getConfig')
                ->will($this->returnValue($someConfig));
        
        $abstractFactory = new ConfigAbstractFactory();
        $moduleConfig = $abstractFactory->createServiceWithName($serviceLocatorMock, $name = 'foo', "$moduleName\\ModuleConfig");
        
        $this->assertInstanceOf('T4webBase\Module\ModuleConfig', $moduleConfig);
        $this->assertAttributeSame($someConfig, 'config', $moduleConfig);
    }

}
