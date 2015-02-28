<?php

namespace Base\Domain\Criteria;

use Base\Db\QueryBuilderInterface;

class EmptyCriteria extends NewCriteria {
    
    public function build(QueryBuilderInterface $queryBuilder) {
        if (!$this->isForeign) {
            $queryBuilder->from($this->table);
        }
    }
    
}
