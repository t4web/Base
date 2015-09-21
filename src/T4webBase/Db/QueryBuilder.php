<?php

namespace T4webBase\Db;

use Zend\Db\Sql\Predicate\IsNotNull;
use Zend\Db\Sql\Predicate\IsNull;
use Zend\Db\Sql\Predicate\NotIn;
use Zend\Db\Sql\Predicate\Expression;

class QueryBuilder implements QueryBuilderInterface {

    private $select     = null;
    private $from       = null;
    private $join       = array();
    private $leftJoin   = array();
    private $where      = array();
    private $orWhere    = array();
    private $offset     = null;
    private $limit      = null;
    private $page       = null;
    private $columns    = array();
    private $group      = array();
    private $order      = null;

    public function __construct(Select $select) {
        $this->select = $select;
    }

    /**
     * @return \T4webBase\Db\Select
     */
    public function getQuery() {
        $this->select->reset();

        if (!empty($this->columns)) {
            $this->select->from($this->from);
            $this->select->columns($this->columns);
        } else {
            $this->select->from($this->from);
            $this->select->columns(array('*'));
        }

        foreach ($this->join as $joinTable => $joinRule) {
            $this->select->join($joinTable, $joinRule, array());
        }

        foreach ($this->leftJoin as $joinTable => $joinRule) {
            $this->select->joinLeft($joinTable, $joinRule, array());
        }

        foreach ($this->where as $cond => $value) {
            $this->select->where($cond, $value);
        }

        foreach ($this->orWhere as $cond => $value) {
            $this->select->orWhere($cond, $value);
        }

        if (!empty($this->group)) {
            $this->select->group($this->group);
        }

        if ($this->offset) {
            $this->select->offset($this->offset);
        }

        if ($this->limit) {
            $this->select->limit($this->limit);
        }

        if ($this->order) {
            $this->select->order($this->order);
        }

        if ($this->limit && $this->page) {
            $this->select->limitPage($this->page, $this->limit);
        }

        /* if this QueryBuilder is involved in more then one request */
        $this->clear();

        return $this->select;
    }

    public function addFilterEqual($name, $value) {
        $this->where["$name = ?"] = $value;

        return $this;
    }

    public function addFilterNotEqual($name, $value) {
        $this->where["$name <> ?"] = $value;

        return $this;
    }

    public function addFilterMoreOrEqual($name, $value) {
        $this->where["$name >= ?"] = $value;

        return $this;
    }

    public function addFilterLessOrEqual($name, $value) {
        $this->where["$name <= ?"] = $value;

        return $this;
    }

    public function addFilterMore($name, $value) {
        $this->where["$name > ?"] = $value;

        return $this;
    }

    public function addFilterLess($name, $value) {
        $this->where["$name < ?"] = $value;

        return $this;
    }

    public function addFilterIn($name, $value) {
        $this->where[$name] = $value;

        return $this;
    }

    public function addFilterNotIn($name, $value) {
        $this->where[] = new NotIn($name, $value);

        return $this;
    }

    public function addFilterLike($name, $value) {
        $this->where["$name LIKE ?"] = "%$value%";

        return $this;
    }

    public function addFilterLikeBefore($name, $value) {
        $this->where["$name LIKE ?"] = "%$value";

        return $this;
    }

    public function addFilterLikeNext($name, $value) {
        $this->where["$name LIKE ?"] = "$value%";

        return $this;
    }

    public function addOrFilterLike($name, $value) {
        $this->orWhere["$name LIKE ?"] = "%$value%";

        return $this;
    }

    public function addFilterLikeByMask($name, $value) {
        $this->where["$name LIKE ?"] = $value;

        return $this;
    }

    public function addFilterIsNull($name) {
        $this->where[] = new IsNull("$name");

        return $this;
    }

    public function addFilterIsNotNull($name) {
        $this->where[] = new IsNotNull("$name");

        return $this;
    }

    public function from($tableName) {
        $this->from = $tableName;

        return $this;
    }

    public function join($joinTableName, $joinRule) {
        $this->join[$joinTableName] = $joinRule;

        return $this;
    }

    public function leftJoin($joinTableName, $joinRule) {
        $this->leftJoin[$joinTableName] = $joinRule;

        return $this;
    }

    public function offset($offset) {
        $this->offset = $offset;

        return $this;
    }

    public function limit($limit) {
        $this->limit = $limit;

        return $this;
    }

    public function page($page) {
        $this->page = $page;

        return $this;
    }

    public function addColumn($columnName, $alias = '') {
        if (empty($alias)) {
            $this->columns[] = $columnName;
            return $this;
        }
        $this->columns[$alias] = $columnName;

        return $this;
    }

    public function group($columnName) {
        $this->group[] = $columnName;

        return $this;
    }

    public function order($order) {
        $this->order = $order;
    }

    public function orFilterEqual($name, $value) {
        $this->orWhere["$name = ?"] = $value;

        return $this;
    }

    /**
     * @param $query
     * @return Expression
     */
    public  function addWhere($query)
    {

        if($query instanceof Expression) {
            return $this->where[] = $query;

        }
        return $this->where[] = new Expression($query);
    }

    private function clear() {
        $this->columns = array();
        $this->join = array();
        $this->where = array();
        $this->group = array();
        $this->order = null;
        $this->from = null;
        $this->orWhere = array();
        $this->leftJoin = array();
        $this->offset = null;
        $this->limit = null;
        $this->page = null;
        /** @todo: write other things (I'm just needing where now) */
    }

}
