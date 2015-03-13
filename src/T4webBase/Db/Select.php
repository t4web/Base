<?php

namespace T4webBase\Db;

use Zend\Db\Sql\Predicate;

class Select implements SelectInterface {
    
    /**
     *
     * @var \Zend\Db\Sql\Select 
     */
    private $zendSelect;
    
    public function __construct(\Zend\Db\Sql\Select $zendSelect) {
        $this->zendSelect = $zendSelect;
    }
    
    public function getZendSelect() {
        return $this->zendSelect;
    }
    
    public function reset($part = null) {
        if (empty($part)) {
            $parts = array(
                'table', 'quantifier', 'columns', 'joins', 'where',
                'group', 'having', 'limit', 'offset', 'order', 'combine'
            );
            
            foreach ($parts as $part) {
                $this->zendSelect->reset($part);
            }
            
            return null;
        }
        
        return $this->zendSelect->reset($part);
    }
    
    public function from($table) {
        return $this->zendSelect->from($table);
    }
    
    public function columns(array $columns) {
        return $this->zendSelect->columns($columns);
    }
    
    public function join($joinTable, $joinRule, $columns) {
        return $this->zendSelect->join($joinTable, $joinRule, $columns);
    }
    
    public function joinLeft($joinTable, $joinRule, $columns) {
        return $this->zendSelect->join($joinTable, $joinRule, $columns, \Zend\Db\Sql\Select::JOIN_LEFT);
    }
    
    public function where($cond, $value) {
        return $this->zendSelect->where(array($cond => $value), Predicate\PredicateSet::OP_AND);
    }
    
    public function orWhere($cond, $value) {
        return $this->zendSelect->where(array($cond => $value), Predicate\PredicateSet::OP_OR);
    }
    
    public function group($group) {
        return $this->zendSelect->group($group);
    }
    
    public function limit($limit) {
        return $this->zendSelect->limit($limit);
    }
    
    public function offset($offset) {
        return $this->zendSelect->offset($offset);
    }
    
    public function order($order) {
        return $this->zendSelect->order($order);
    }
     
    public function limitPage($page, $rowCount) {
        $page     = ($page > 0)     ? $page     : 1;
        $rowCount = ($rowCount > 0) ? $rowCount : 1;
        
        $this->limit((int)$rowCount) ;
        $this->offset((int)$rowCount * ($page - 1));
        
        return $this;
    }
}
