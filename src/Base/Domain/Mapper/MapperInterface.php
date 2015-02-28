<?php

namespace Base\Domain\Mapper;

use Base\Domain\EntityInterface;
use Base\Db\Select;

interface MapperInterface {
    
    public function create(EntityInterface $entity, $id = null);
    
    public function update(EntityInterface $entity);
    
    public function findMany(Select $select);
    
    public function findOne(Select $select);
    
}
