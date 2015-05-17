<?php

namespace T4webBaseTest\Db;

use Zend\Db\TableGateway\Feature\GlobalAdapterFeature;
use T4webBase\Db\TableGatewayAbstractFactory;

class TableGatewayAbstractFactoryTest extends \PHPUnit_Framework_TestCase {

    private $abstractFactory;

    public function setUp()
    {
        $this->abstractFactory = new TableGatewayAbstractFactory();
    }

    public function testCanCreateServiceWithName() {
        $serviceLocatorMock = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface');

        $this->assertTrue(
            $this->abstractFactory->canCreateServiceWithName($serviceLocatorMock, 'foo', 'ModuleName\Entity\TableGateway'));
        $this->assertFalse(
            $this->abstractFactory->canCreateServiceWithName($serviceLocatorMock, 'foo', 'ModuleName\Entity\TableGatewayX'));
    }

    public function testCreateServiceWithName() {
        $tableName = 'foo';
        
        $serviceLocatorMock = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface');

        $moduleConfigMock = $this->getMockBuilder('T4webBase\Module\ModuleConfig')
            ->disableOriginalConstructor()
            ->getMock();

        $serviceLocatorMock->expects($this->at(0))
                ->method('get')
                ->with($this->equalTo('ModuleName\ModuleConfig'))
                ->will($this->returnValue($moduleConfigMock));

        $moduleConfigMock->expects($this->once())
                ->method('getDbTableName')
                ->with($this->equalTo('modulename-entity'))
                ->will($this->returnValue($tableName));

        $dbAdapterMock = $this->getDbAdapterMock();

        GlobalAdapterFeature::setStaticAdapter($dbAdapterMock);

        $tableGateway = $this->abstractFactory->createServiceWithName(
            $serviceLocatorMock, $name = 'foo', 'ModuleName\Entity\TableGateway');
        
        $this->assertInstanceOf('T4webBase\Db\TableGateway', $tableGateway);
        $this->assertAttributeSame($tableName, 'table', $tableGateway);
    }

    private function getDbAdapterMock()
    {
        $dbAdapterMock = $this->getMockBuilder('Zend\Db\Adapter\Adapter')
            ->disableOriginalConstructor()
            ->getMock();

        $platformMock = $this->getMock('Zend\Db\Adapter\Platform\PlatformInterface');

        $platformMock->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('mysql'));

        $dbAdapterMock->expects($this->any())
            ->method('getPlatform')
            ->will($this->returnValue($platformMock));

        return $dbAdapterMock;
    }
}
