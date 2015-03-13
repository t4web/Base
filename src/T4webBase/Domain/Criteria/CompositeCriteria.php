<?php

namespace T4webBase\Domain\Criteria;

use T4webBase\Db\QueryBuilderInterface;

class CompositeCriteria implements CriteriaInterface {
    
    private $children;
    
    public function add(CriteriaInterface $criteria) {
        $this->children[] = $criteria;
    }
    
    public function build(QueryBuilderInterface $queryBuilder) {
        foreach ($this->children as $child) {
            $child->build($queryBuilder);
        }
    }
    
}
