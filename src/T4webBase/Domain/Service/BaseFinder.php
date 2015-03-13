<?php

namespace T4webBase\Domain\Service;

use T4webBase\Domain\Repository\DbRepository;
use T4webBase\Domain\Criteria\Factory as CriteriaFactory;

class BaseFinder {
    
    protected $repositoryDb;
    protected $criteriaFactory;

    public function __construct(DbRepository $repositoryDb, CriteriaFactory $criteriaFactory) {
        $this->repositoryDb = $repositoryDb;
        $this->criteriaFactory = $criteriaFactory;
    }
    
    public function find($filter) {
        $criteria = $this->criteriaFactory->create($filter);
        
        return $this->repositoryDb->find($criteria);
    }
    
    public function findMany($filter = array()) {
        $criteria = $this->criteriaFactory->create($filter);
        
        return $this->repositoryDb->findMany($criteria);
    }
    
    public function count($filter) {
        $criteria = $this->criteriaFactory->create($filter);

        return $this->repositoryDb->count($criteria);
    }    
    
    public function findByFilter($filter = array(), $limit = 20, $page = 1) {
        $criteria = $this->criteriaFactory->create($filter);
        
        $criteria->add($this->criteriaFactory->getNativeCriteria("Limit", $limit));
        $criteria->add($this->criteriaFactory->getNativeCriteria("Page", $page));
        
        return $this->repositoryDb->findMany($criteria);
    }

    public function findManyByColumns(array $filter, array $columns) {
        $criteria = $this->criteriaFactory->create($filter);

        return $this->repositoryDb->findManyByColumns($criteria, $columns);
    }
}
