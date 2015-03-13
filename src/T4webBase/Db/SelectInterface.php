<?php

namespace T4webBase\Db;

interface SelectInterface {
    
    public function getZendSelect();
    
    public function reset($part = null);
    
    public function from($name);
    
    public function columns(array $columns);
    
    public function join($joinTable, $joinRule, $columns);
    
    public function joinLeft($joinTable, $joinRule, $columns);
    
    public function where($cond, $value);
    
    public function orWhere($cond, $value);
    
    public function group($group);
    
    public function limit($limit);
    
    public function offset($offset);
    
    public function order($order);
    
    public function limitPage($page, $rowCount);
}
