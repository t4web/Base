<?php

namespace Base\Domain\Factory;

class EntityFactory implements EntityFactoryInterface {
    
    protected $entityClass;
    protected $collectionClass;

    public function __construct($entityClass, $collectionClass = 'Base\Domain\Collection') {
        $this->entityClass = $entityClass;
        $this->collectionClass = $collectionClass;
    }
    
    public function create(array $data) {
        return new $this->entityClass($data);
    }
    
    public function createCollection(array $data) {
        $collection = new $this->collectionClass();
        
        foreach ($data as $value) {
            $entity = $this->create($value);
            $collection->offsetSet($entity->getId(), $entity);
        }
        
        return $collection;
    }
    
}
