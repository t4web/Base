<?php

namespace T4webBaseTest\Db;

use T4webBase\Db\Select;
use T4webBase\Db\Table;

class TableTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var Table
     */
    private $table;
    private $tableGatewayMock;

    public function setUp() {
        $this->tableGatewayMock = $this->getMockBuilder('T4webBase\Db\TableGateway')
            ->disableOriginalConstructor()
            ->getMock();

        $this->table = new Table($this->tableGatewayMock, 'id');
    }

    public function testConstructor()
    {
        $this->assertAttributeEquals($this->tableGatewayMock, 'tableGateway', $this->table);
        $this->assertAttributeEquals('id', 'primaryKey', $this->table);
    }

    public function testGetName()
    {
        $this->tableGatewayMock->expects($this->once())
            ->method('getTable')
            ->will($this->returnValue('table_name'));

        $name = $this->table->getName();

        $this->assertEquals('table_name', $name);
    }

    public function testInsert()
    {
        $data = ['key' => 'value'];

        $this->tableGatewayMock->expects($this->once())
            ->method('insert')
            ->with($this->equalTo($data));

        $this->table->insert($data);
    }

    public function testGetLastInsertId()
    {
        $this->tableGatewayMock->expects($this->once())
            ->method('getLastInsertValue')
            ->will($this->returnValue(11));

        $this->assertEquals(11, $this->table->getLastInsertId());
    }

    public function testSelectMany()
    {
        $select = $this->getMockBuilder('T4webBase\Db\Select')
            ->disableOriginalConstructor()
            ->getMock();
        $result = $this->getMock('Zend\Db\ResultSet\ResultSet');

        $this->tableGatewayMock->expects($this->once())
            ->method('selectWith')
            ->will($this->returnValue($result));

        $select->expects($this->once())
            ->method('getZendSelect')
            ->will($this->returnValue($this->getMock('Zend\Db\Sql\Select')));

        $result->expects($this->once())
            ->method('toArray');

        $this->table->selectMany($select);
    }

    public function testSelectOne()
    {
        $select = $this->getMockBuilder('T4webBase\Db\Select')
            ->disableOriginalConstructor()
            ->getMock();
        $result = $this->getMock('Zend\Db\ResultSet\ResultSet');

        $this->tableGatewayMock->expects($this->once())
            ->method('selectWith')
            ->will($this->returnValue($result));

        $select->expects($this->once())
            ->method('limit')
            ->with($this->equalTo(1))
            ->will($this->returnSelf());

        $select->expects($this->once())
            ->method('offset')
            ->with($this->equalTo(0))
            ->will($this->returnSelf());

        $select->expects($this->once())
            ->method('getZendSelect')
            ->will($this->returnValue($this->getMock('Zend\Db\Sql\Select')));

        $result->expects($this->once())
            ->method('toArray')
            ->will($this->returnValue([]));

        $this->table->selectOne($select);
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

    public function testUpdateByPrimaryKey()
    {
        $data = [];
        $primaryKeyValue = 1;

        $this->tableGatewayMock->expects($this->once())
            ->method('update')
            ->with($this->equalTo($data), $this->equalTo(array("id = $primaryKeyValue")));

        $this->table->updateByPrimaryKey($data, $primaryKeyValue);
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

    public function testDeleteByPrimaryKey()
    {
        $primaryKeyValue = 1;

        $this->tableGatewayMock->expects($this->once())
            ->method('delete')
            ->with($this->equalTo(array("id = $primaryKeyValue")));

        $this->table->deleteByPrimaryKey($primaryKeyValue);
    }

    public function testDelete()
    {
        $conditions = [];

        $this->tableGatewayMock->expects($this->once())
            ->method('delete')
            ->with($this->equalTo($conditions));

        $this->table->delete($conditions);
    }
    
}
