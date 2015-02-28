<?php

namespace Base\Domain\Criteria;

use Base\Db\QueryBuilderInterface;

abstract class AbstractOrderByCriteria extends AbstractCriteria {
    
    protected $buildMethod = 'order';
    
    public function __construct($field = null) {
        $this->field = $field;
    }
    
    protected function callBuildMethod(QueryBuilderInterface $queryBuilder) {
        $queryBuilder->{$this->buildMethod}($this->table . "." . $this->field);
    }
    
}
