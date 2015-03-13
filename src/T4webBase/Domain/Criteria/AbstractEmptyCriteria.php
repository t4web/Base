<?php

namespace T4webBase\Domain\Criteria;

use T4webBase\Db\QueryBuilderInterface;

abstract class AbstractEmptyCriteria extends AbstractCriteria {
    
    public function build(QueryBuilderInterface $queryBuilder) {
        if (!$this->isForeign) {
            $queryBuilder->from($this->table);
        }
    }
    
}
