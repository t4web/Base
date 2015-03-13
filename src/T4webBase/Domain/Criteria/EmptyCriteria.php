<?php

namespace T4webBase\Domain\Criteria;

use T4webBase\Db\QueryBuilderInterface;

class EmptyCriteria extends NewCriteria {
    
    public function build(QueryBuilderInterface $queryBuilder) {
        if (!$this->isForeign) {
            $queryBuilder->from($this->table);
        }
    }
    
}
