<?php

namespace BaseTest\Db\QueryBuilder;

use Base\Db\QueryBuilder;

class QueryBuilderTest extends \PHPUnit_Framework_TestCase {
    
    private $dbQueryBuilder;
    private $selectMock;
    
    public function setUp() {
        $this->selectMock = $this->getMockBuilder('Base\Db\Select')
                ->disableOriginalConstructor()
                ->getMock();
        $this->dbQueryBuilder = new QueryBuilder($this->selectMock);
    }
    
    public function testConstructor() {
        $this->assertAttributeEquals($this->selectMock, 'select', $this->dbQueryBuilder);
        $this->assertAttributeEquals(null, 'from', $this->dbQueryBuilder);
        $this->assertAttributeEquals(array(), 'join', $this->dbQueryBuilder);
        $this->assertAttributeEquals(array(), 'where', $this->dbQueryBuilder);
    }
    
    public function testGetQueryReturnSelect() {
        $this->assertSame($this->selectMock, $this->dbQueryBuilder->getQuery());
    }
    
    public function testAddFilterEqual() {
        $fieldName = 'field_name';
        $fieldValue = 'field_value';
        
        $this->dbQueryBuilder->addFilterEqual($fieldName, $fieldValue);
        
        $this->assertAttributeEquals(array("$fieldName = ?" => $fieldValue), 'where', $this->dbQueryBuilder);
    }
    
    public function testAddFilterMoreOrEqual() {
        $fieldName = 'field_name';
        $fieldValue = 'field_value';
        
        $this->dbQueryBuilder->addFilterMoreOrEqual($fieldName, $fieldValue);
        
        $this->assertAttributeEquals(array("$fieldName >= ?" => $fieldValue), 'where', $this->dbQueryBuilder);
    }
    
    public function testAddFilterLessOrEqual() {
        $fieldName = 'field_name';
        $fieldValue = 'field_value';
        
        $this->dbQueryBuilder->addFilterLessOrEqual($fieldName, $fieldValue);
        
        $this->assertAttributeEquals(array("$fieldName <= ?" => $fieldValue), 'where', $this->dbQueryBuilder);
    }
    
    public function testAddFilterIn() {
        $fieldName = 'field_name';
        $fieldValue = array('field_value_1', 'field_value_2');
        
        $this->dbQueryBuilder->addFilterIn($fieldName, $fieldValue);
        
        $this->assertAttributeEquals(array("$fieldName" => $fieldValue), 'where', $this->dbQueryBuilder);
    }
    
    public function testFrom() {
        $tableName = 'table_name';
        
        $this->dbQueryBuilder->from($tableName);
        
        $this->assertAttributeEquals($tableName, 'from', $this->dbQueryBuilder);
    }
    
    public function testJoin() {
        $joinTableName = 'join_table_name';
        $joinRule = 'join_rule';
        
        $this->dbQueryBuilder->join($joinTableName, $joinRule);
        
        $this->assertAttributeEquals(array($joinTableName => $joinRule), 'join', $this->dbQueryBuilder);
    }
    
    public function testGetQuery() {
        $this->selectMock->expects($this->at(0))
                ->method('reset');
        $this->selectMock->expects($this->at(1))
                ->method('from')
                ->with($this->equalTo('table_name'));
        $this->selectMock->expects($this->at(2))
                ->method('columns')
                ->with($this->equalTo(array('*')));
        $this->selectMock->expects($this->at(3))
                ->method('join')
                ->with(
                    $this->equalTo('join_table_1'),
                    $this->equalTo('table_name.field_1 = join_table_1.field_3'),
                    array()
                );
        $this->selectMock->expects($this->at(4))
                ->method('join')
                ->with(
                    $this->equalTo('join_table_2'),
                    $this->equalTo('table_name.field_1 = join_table_2.field_3'),
                    array()
                );
         $this->selectMock->expects($this->at(5))
                ->method('where')
                ->with(
                    $this->equalTo('join_table_1.field_1 = ?'),
                    $this->equalTo('value_1')
                );
        $this->selectMock->expects($this->at(6))
                ->method('where')
                ->with(
                    $this->equalTo('join_table_2.field_2 = ?'),
                    $this->equalTo('value_2')
                );
        
        $this->dbQueryBuilder->from('table_name')
                ->join('join_table_1', 'table_name.field_1 = join_table_1.field_3')
                ->addFilterEqual('join_table_1.field_1', 'value_1')
                ->join('join_table_2', 'table_name.field_1 = join_table_2.field_3')
                ->addFilterEqual('join_table_2.field_2', 'value_2');
        
        $this->dbQueryBuilder->getQuery();
    }
     /**
      * @dataProvider dataForAddColumn
      */
    public function testAddColumn($alias = '') {
        $columns = array();
        $columnName = 'columnName';
        if(empty($alias)) {
            $columns[] = $columnName;
        } else {
            $columns[$alias] = $columnName;
        }
        $this->dbQueryBuilder->addColumn($columnName, $alias);

        $this->assertAttributeEquals($columns, 'columns', $this->dbQueryBuilder);
    }

    public function dataForAddColumn(){
        return array(
            array('alias'),
            array(),
        );
    }
}
