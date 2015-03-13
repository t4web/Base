<?php

namespace T4webBase\Domain\Criteria;

use T4webBase\Db\QueryBuilderInterface;

abstract class AbstractCriteria implements CriteriaInterface {
    
    protected $value;
    
    protected $field;
    
    protected $table;
    
    protected $buildMethod;
    
    protected $isForeign = false;
    
    protected $joinTable;
    
    protected $joinRule;
    
    public function __construct($value = null) {
        $this->value = $value;
    }

    public function getField() {
        return $this->field;
    }

    public function setAsForeign() {
        $this->isForeign = true;
        return $this;
    }
    
    public function setJoinTable($joinTable) {
        $this->joinTable = $joinTable;
        return $this;
    }

    public function setJoinRule($joinRule) {
        $this->joinRule = $joinRule;
        return $this;
    }

    public function build(QueryBuilderInterface $queryBuilder) {
        if ($this->isForeign) {
            $queryBuilder->join($this->joinTable, $this->joinRule);
        } else {
            $queryBuilder->from($this->table);
        }
        
        $this->callBuildMethod($queryBuilder);
    }
    
    protected function callBuildMethod(QueryBuilderInterface $queryBuilder) {
        if (in_array($this->buildMethod, array('group', 'limit', 'page'))) {
            $queryBuilder->{$this->buildMethod}($this->value);
            return;
        }

        $queryBuilder->{$this->buildMethod}("$this->table.$this->field", $this->prepareValue());
    }

    protected function prepareValue() {
        $result = $this->value;
        if (is_string($this->value)) {
            $result = htmlentities($this->value, ENT_QUOTES);
        }

        if (is_array($this->value)) {
            $result = array_map('htmlentities', $this->value, array_fill_keys(array_keys($this->value), ENT_QUOTES));
        }

        return $result;
    }
    
}
