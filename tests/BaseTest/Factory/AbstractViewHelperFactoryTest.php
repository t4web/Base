<?php

namespace BaseTest\Factory;

abstract class AbstractViewHelperFactoryTest extends AbstractFactoryTest {

    protected $pluginManager;

    public function setUp() {
        parent::setUp();

        $this->pluginManager = $this->getMock('Zend\ServiceManager\AbstractPluginManager');
        $this->pluginManager->expects($this->once())
            ->method('getServiceLocator')
            ->will($this->returnValue($this->serviceManager));
    }
}
