<?php

namespace T4webBase\Domain\Mapper;

use T4webBase\Domain\EntityInterface;
use T4webBase\Db\Select;

interface MapperInterface {
    
    public function create(EntityInterface $entity, $id = null);
    
    public function update(EntityInterface $entity);
    
    public function findMany(Select $select);
    
    public function findOne(Select $select);
    
}
