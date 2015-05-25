<?php

namespace T4webBaseTest\Db;

use Zend\Db\Sql\Predicate;
use T4webBase\Db\Select;

class SelectTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var Select
     */
    private $select;
    private $zendSelectMock;

    protected function setUp()
    {
        $this->zendSelectMock = $this->getMock('Zend\Db\Sql\Select');

        $this->select = new Select($this->zendSelectMock);
    }
    
    public function testConstructor() {
        $this->assertAttributeEquals($this->zendSelectMock, 'zendSelect', $this->select);
        $this->assertEquals($this->zendSelectMock, $this->select->getZendSelect());
    }

    public function testResetWithPart() {
        $part = 'table';

        $this->zendSelectMock->expects($this->once())
            ->method('reset')
            ->with($this->equalTo($part));

        $this->select->reset($part);
    }

    public function testResetWithoutPart() {
        $parts = array(
            'table', 'quantifier', 'columns', 'joins', 'where',
            'group', 'having', 'limit', 'offset', 'order', 'combine'
        );

        foreach ($parts as $at => $part) {
            $this->zendSelectMock->expects($this->at($at))
                ->method('reset')
                ->with($this->equalTo($part));
        }

        $this->select->reset();
    }

    public function testFrom() {
        $table = 'table';

        $this->zendSelectMock->expects($this->once())
            ->method('from')
            ->with($this->equalTo($table));

        $this->select->from($table);
    }

    public function testColumns() {
        $columns = ['column'];

        $this->zendSelectMock->expects($this->once())
            ->method('columns')
            ->with($this->equalTo($columns));

        $this->select->columns($columns);
    }

    public function testJoin() {
        $joinTable = 'table';
        $joinRule = 'rule';
        $columns = ['column'];

        $this->zendSelectMock->expects($this->once())
            ->method('join')
            ->with(
                $this->equalTo($joinTable),
                $this->equalTo($joinRule),
                $this->equalTo($columns)
            );

        $this->select->join($joinTable, $joinRule, $columns);
    }

    public function testJoinLeft() {
        $joinTable = 'table';
        $joinRule = 'rule';
        $columns = ['column'];

        $this->zendSelectMock->expects($this->once())
            ->method('join')
            ->with(
                $this->equalTo($joinTable),
                $this->equalTo($joinRule),
                $this->equalTo($columns)
            );

        $this->select->joinLeft($joinTable, $joinRule, $columns);
    }

    public function testWhere() {
        $cond = 'condition';
        $value = 'value';

        $this->zendSelectMock->expects($this->once())
            ->method('where')
            ->with(
                $this->equalTo([$cond => $value]),
                $this->equalTo(Predicate\PredicateSet::OP_AND)
            );

        $this->select->where($cond, $value);
    }

    public function testOrWhere() {
        $cond = 'condition';
        $value = 'value';

        $this->zendSelectMock->expects($this->once())
            ->method('where')
            ->with(
                $this->equalTo([$cond => $value]),
                $this->equalTo(Predicate\PredicateSet::OP_OR)
            );

        $this->select->orWhere($cond, $value);
    }

    public function testGroup() {
        $group = 'group condition';

        $this->zendSelectMock->expects($this->once())
            ->method('group')
            ->with($this->equalTo($group));

        $this->select->group($group);
    }

    public function testLimit() {
        $limit = 99;

        $this->zendSelectMock->expects($this->once())
            ->method('limit')
            ->with($this->equalTo($limit));

        $this->select->limit($limit);
    }

    public function testOffset() {
        $offset = 99;

        $this->zendSelectMock->expects($this->once())
            ->method('offset')
            ->with($this->equalTo($offset));

        $this->select->offset($offset);
    }

    public function testOrder() {
        $order = 'field';

        $this->zendSelectMock->expects($this->once())
            ->method('order')
            ->with($this->equalTo($order));

        $this->select->order($order);
    }

    public function testLimitPage() {
        $page = 3;
        $rowCount = 20;

        $this->zendSelectMock->expects($this->once())
            ->method('limit')
            ->with($this->equalTo($rowCount));

        $this->zendSelectMock->expects($this->once())
            ->method('offset')
            ->with($this->equalTo($rowCount * ($page - 1)));

        $this->select->limitPage($page, $rowCount);
    }

}
