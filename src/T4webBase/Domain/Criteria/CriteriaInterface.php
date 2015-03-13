<?php

namespace T4webBase\Domain\Criteria;

use T4webBase\Domain\EntityInterface;
use T4webBase\Db\QueryBuilderInterface;

interface CriteriaInterface {
    
    //public function isSatisfiedBy(EntityInterface $entity);
    
    public function build(QueryBuilderInterface $queryBuilder);
    
}
