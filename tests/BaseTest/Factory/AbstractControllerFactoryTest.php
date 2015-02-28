<?php

namespace BaseTest\Factory;

abstract class AbstractControllerFactoryTest extends AbstractFactoryTest {

    protected $controllerManager;

    public function setUp() {
        parent::setUp();

        $this->controllerManager = $this->getMock('Zend\Mvc\Controller\ControllerManager');
        $this->controllerManager->expects($this->once())
            ->method('getServiceLocator')
            ->will($this->returnValue($this->serviceManager));
    }
}
