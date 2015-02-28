<?php

namespace Base\Domain\Criteria;

use Base\Domain\EntityInterface;
use Base\Db\QueryBuilderInterface;

interface CriteriaInterface {
    
    //public function isSatisfiedBy(EntityInterface $entity);
    
    public function build(QueryBuilderInterface $queryBuilder);
    
}
