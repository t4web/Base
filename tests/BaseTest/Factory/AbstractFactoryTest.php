<?php

namespace BaseTest\Factory;

use Zend\ServiceManager\ServiceManager;

abstract class AbstractFactoryTest extends \PHPUnit_Framework_TestCase {

    /** @var ServiceManager  */
    protected $serviceManager;

    public function setUp() {
        $this->serviceManager = new ServiceManager();
    }
}
