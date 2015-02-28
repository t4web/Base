<?php

namespace Base\Domain\Criteria;

use Base\Db\QueryBuilderInterface;

abstract class AbstractEmptyCriteria extends AbstractCriteria {
    
    public function build(QueryBuilderInterface $queryBuilder) {
        if (!$this->isForeign) {
            $queryBuilder->from($this->table);
        }
    }
    
}
