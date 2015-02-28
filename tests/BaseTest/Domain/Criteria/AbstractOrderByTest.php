<?php


namespace BaseTest\Domain\Criteria;

use BaseTest\Domain\Criteria\TestAsset\CriteriaWithExceptionBuildMethod;

class AbstractOrderByTest extends \PHPUnit_Framework_TestCase {
    
    protected $testClass = 'BaseTest\Domain\Criteria\TestAsset\CriteriaWithExceptionBuildMethod';
    protected $testValue = 'id';
    protected $testTable = 'fooTable';

    public function testBuildExceptionMethod() {
        $criteria = new CriteriaWithExceptionBuildMethod($this->testValue);

        $queryBuilderMock = $this->getMock('Base\Db\QueryBuilderInterface');
        $queryBuilderMock->expects($this->at(0))
            ->method('from')
            ->with($this->equalTo($this->testTable))
            ->will($this->returnValue($queryBuilderMock));

        $queryBuilderMock->expects($this->at(1))
            ->method('group')
            ->with($this->equalTo($this->testValue));

        $criteria->build($queryBuilderMock);
    }
    
}