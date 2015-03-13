<?php

namespace T4webBase\Domain\Criteria;

use T4webBase\Db\QueryBuilderInterface;

abstract class Id extends AbstractCriteria {
    
    private $id;
    
    public function __construct($id) {
        $this->id = $id;
    }
    
    public function build(QueryBuilderInterface $queryBuilder) {
        
        if ($this->isForeign) {
            $queryBuilder->join($this->joinTable, $this->joinRule);
        } else {
            $queryBuilder->from($this->table);
        }
        
        $queryBuilder->addFilterEqual("$this->table.id", $this->id);
    }
    
}