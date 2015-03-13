<?php

namespace T4webBaseTest\Db;

use T4webBase\Db\Select;
use T4webBase\Db\Table;

class TableTest extends \PHPUnit_Framework_TestCase {

    private $table;
    private $tableGatewayMock;

    public function setUp() {
        $this->tableGatewayMock = $this->getMockBuilder('T4webBase\Db\TableGateway')
            ->disableOriginalConstructor()
            ->getMock();

        $this->table = new Table($this->tableGatewayMock, 'id');
    }

    /**
     * @dataProvider dataProvider
     */
    public function testDeleteByAttribute($attributeName, $primaryKeyValue, $expected) {
        $this->tableGatewayMock->expects($this->once())
            ->method('delete')
            ->with($this->equalTo(array($expected)));

        $this->table->deleteByAttribute($attributeName, $primaryKeyValue);
    }

    public function dataProvider() {
        return array(
            array('id', array(1, 2), "id IN (1,2)"),
            array('product_id', array(1, 2, 5), "product_id IN (1,2,5)"),
            array('id', 1, "id IN (1)")
        );
    }

    /**
     * @dataProvider dataCountProvider
     */
    public function testCount($resultData, $expectedCount) {
        $select = new Select(new \Zend\Db\Sql\Select());
        $this->table = $this->getMock(
            'T4webBase\Db\Table',
            array('selectMany'),
            array($this->tableGatewayMock, 'id')
        );

        $this->table->expects($this->once())
            ->method('selectMany')
            ->with($this->equalTo($select))
            ->will($this->returnValue($resultData));

        $this->assertEquals($expectedCount, $this->table->count($select));
    }

    public function dataCountProvider() {
        return array(
            array(array(array('count' => 1), array('count' => 3), array('count' => 5)), 3),
            array(array(array('count' => 1), array('count' => 1)), 2),
            array(array(array('count' => 6)), 6),
            array(array(), 0)
        );
    }

    /**
     * @dataProvider dataProviderUpdate
     */
    public function testUpdateByAttribute($attributeName, $primaryKeyValue, $expected) {
        $data = array();
        $this->tableGatewayMock->expects($this->once())
            ->method('update')
            ->with($this->equalTo($data), $this->equalTo(array($expected)));

        $this->table->updateByAttribute($data, $attributeName, $primaryKeyValue);
    }

    public function dataProviderUpdate() {
        return array(
            array('id', array(1, 2), "id IN (1,2)"),
            array('product_id', array(1, 2, 5), "product_id IN (1,2,5)"),
            array('id', 1, "id IN (1)")
        );
    }
    
}
