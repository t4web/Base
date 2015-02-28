<?php

namespace BaseTest\Domain\Mapper;

use Base\Domain\Mapper\DbMapper;

class DbMapperTest extends \PHPUnit_Framework_TestCase {

    private $dbMapper;
    private $columnsAsAttributesMap;
    private $factoryMock;

    public function setUp() {
        $this->columnsAsAttributesMap = array(
            'column' => 'attribute'
        );
        $this->factoryMock = $this->getMock('Base\Domain\Factory\EntityFactoryInterface');
        $this->dbMapper = new DbMapper($this->columnsAsAttributesMap, $this->factoryMock);
    }
    
    public function testImplementMapperInterface() {
        $this->assertInstanceOf('Base\Domain\Mapper\DbMapperInterface', $this->dbMapper);
    }
    
    public function testConstructor() {
        $this->assertAttributeEquals($this->columnsAsAttributesMap, 'columnsAsAttributesMap', $this->dbMapper);
        $this->assertAttributeEquals($this->factoryMock, 'factory', $this->dbMapper);
    }
    
    public function testToTableRow() {
        $hydratingObjectMock = $this->getMock('Base\Object\HydratingObjectInterface');
        $hydratingObjectMock->expects($this->once())
                ->method('extract')
                ->with(array('attribute'))
                ->will($this->returnValue(array('attribute' => 'attribute value')));
        
        $actualTableRow = $this->dbMapper->toTableRow($hydratingObjectMock);
        
        $expectedTableRow = array(
            'column' => 'attribute value'
        );
        
        $this->assertEquals($expectedTableRow, $actualTableRow);
    }
    
    /**
     * @dataProvider providerFromTableRow 
     */
    public function testFromTableRow($row, $expectedDataForFactory) {
        $hydratingObjectMock = $this->getMock('Base\Object\HydratingObjectInterface');
        
        $this->factoryMock->expects($this->once())
                ->method('create')
                ->with($expectedDataForFactory)
                ->will($this->returnValue($hydratingObjectMock));
        
        $actualHydratingObject = $this->dbMapper->fromTableRow($row);
        
        $this->assertSame($hydratingObjectMock, $actualHydratingObject);
    }
    
    public function providerFromTableRow() {
        return array(
            array(array('column' => 'attribute value'), array('attribute' => 'attribute value')),
            array(array('other_column' => 'other attribute value'), array()),
        );
    }
    
    public function testFromTableRows() {
        $rows = array(array('column' => 'attribute value'));
        $expectedDataForFactory = array(array('attribute' => 'attribute value'));
        
        $hydratingObjectMock = $this->getMock('Base\Object\HydratingObjectInterface');
        
        $this->factoryMock->expects($this->once())
                ->method('createCollection')
                ->with($expectedDataForFactory)
                ->will($this->returnValue($hydratingObjectMock));
        
        $actualHydratingObject = $this->dbMapper->fromTableRows($rows);
        
        $this->assertSame($hydratingObjectMock, $actualHydratingObject);
    }
}
