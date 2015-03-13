<?php

namespace T4webBaseTest\Domain\Criteria;

class AbstractCriteriaTest extends \PHPUnit_Framework_TestCase {
    
    protected $testClass = 'T4webBaseTest\Domain\Criteria\TestAsset\FooCriteria';
    protected $testValue = 'bar';
    protected $testFiled = 'foo';
    protected $testTable = 'fooTable';
    protected $testBuildMethod = 'addFilterMoreOrEqual';
    
    public function testAttributes() {
        $criteria = $this->createCriteria();
        
        $this->assertAttributeEquals($this->testValue, 'value', $criteria);
        $this->assertAttributeEquals($this->testFiled, 'field', $criteria);
        $this->assertAttributeEquals($this->testTable, 'table', $criteria);
        $this->assertAttributeEquals($this->testBuildMethod, 'buildMethod', $criteria);
    }
    
    public function testBuild() {
        $criteria = $this->createCriteria();
        
        $queryBuilderMock = $this->getMock('T4webBase\Db\QueryBuilderInterface');
        $queryBuilderMock->expects($this->at(0))
                ->method('from')
                ->with($this->equalTo($this->testTable));
        
        $this->initCallBuildMethod($queryBuilderMock);
        
        $criteria->build($queryBuilderMock);
    }
    
    public function testBuildAsForeign() {
        $criteria = $this->createCriteria();
        
        $criteria->setAsForeign()
            ->setJoinTable('joinTable')
            ->setJoinRule('joinRule');
        
        $queryBuilderMock = $this->getMock('T4webBase\Db\QueryBuilderInterface');
        $queryBuilderMock->expects($this->at(0))
                ->method('join')
                ->with($this->equalTo('joinTable'), $this->equalTo('joinRule'));

        $this->initCallBuildMethod($queryBuilderMock);
        
        $criteria->build($queryBuilderMock);
    }

    /**
     * @dataProvider PrepareValueProvider
     */
    public function testPrepareValue($value, $expectedValue) {
        $criteria = new $this->testClass($value);
        $criteria->setAsForeign()
            ->setJoinTable('joinTable')
            ->setJoinRule('joinRule');

        $queryBuilderMock = $this->getMock('T4webBase\Db\QueryBuilderInterface');
        $queryBuilderMock->expects($this->at(0))
            ->method('join')
            ->with($this->equalTo('joinTable'), $this->equalTo('joinRule'));

        $queryBuilderMock->expects($this->at(1))
            ->method($this->testBuildMethod)
            ->with($this->equalTo("$this->testTable.$this->testFiled"), $this->equalTo($expectedValue));

        $criteria->build($queryBuilderMock);
    }

    public function PrepareValueProvider() {
        return array(
            array('1', 1),
            array('test', 'test'),
            array('test \'value\'', 'test &#039;value&#039;'),
            array('test "doubleValue"', 'test &quot;doubleValue&quot;'),
            array(array('array "value1"', 'array \'value2\''), array('array &quot;value1&quot;', 'array &#039;value2&#039;')),
        );
    }
    
    protected function createCriteria() {
        return new $this->testClass($this->testValue);
    }
    
    protected function initCallBuildMethod($queryBuilderMock) {
        $queryBuilderMock->expects($this->at(1))
            ->method($this->testBuildMethod)
            ->with($this->equalTo("$this->testTable.$this->testFiled"), $this->equalTo($this->testValue));
    }
    
}