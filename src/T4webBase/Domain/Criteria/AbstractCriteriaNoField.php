<?php

namespace T4webBase\Domain\Criteria;

use T4webBase\Db\QueryBuilderInterface;

abstract class AbstractCriteriaNoField extends AbstractCriteria {

    protected function callBuildMethod(QueryBuilderInterface $queryBuilder) {
        $queryBuilder->{$this->buildMethod}($this->value);
    }
    
}
