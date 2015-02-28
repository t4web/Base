<?php

namespace Base\Domain\Criteria;

use Base\Db\QueryBuilderInterface;

abstract class AbstractCriteriaNoField extends AbstractCriteria {

    protected function callBuildMethod(QueryBuilderInterface $queryBuilder) {
        $queryBuilder->{$this->buildMethod}($this->value);
    }
    
}
