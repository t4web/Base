<?php

namespace Base\Domain\Service;

use Base\Domain\Repository\DbRepository;
use Base\Domain\Criteria\Factory as CriteriaFactory;
use Zend\EventManager\EventManager;
use Base\Domain\Criteria\AbstractCriteria;

class Delete implements DeleteInterface {
    
    /**
     *
     * @var \Base\Domain\Repository\DbRepository
     */
    protected $repository;
    protected $eventManager;
    /**
     *
     * @var \Base\Domain\Criteria\Factory
     */
    protected $criteriaFactory;
    
    public function __construct(
            DbRepository $repository,
            CriteriaFactory $criteriaFactory,
            EventManager $eventManager = null) {
        
        $this->repository = $repository;
        $this->criteriaFactory = $criteriaFactory;
        $this->eventManager = $eventManager;
    }
    
    public function delete($id, $attribyteName = 'Id') {
        $entity = $this->repository->find($this->criteriaFactory->getNativeCriteria($attribyteName, $id));
        if (!$entity) {
            return false;
        }
        
        $this->repository->delete($entity);

        $this->trigger('delete:post', $entity, 'entity');
        
        return $entity;
    }
    
    public function deleteAll($attributeValue, $attributeName = 'Id') {
        /** @var $criteria AbstractCriteria */
        $criteria = $this->criteriaFactory->getNativeCriteria($attributeName, $attributeValue);
        $collection = $this->repository->findMany($criteria);
        if (!$collection->count()) {
            return false;
        }

        $this->repository->deleteByAttribute($attributeValue, $criteria->getField());

        $this->trigger('deleteAll:post', $collection, 'collection');

        return $collection;
    }

    protected function trigger($event, $values, $nameForReturnValue) {
        if (!$this->eventManager) {
            return;
        }

        $this->eventManager->trigger($event, $this, array($nameForReturnValue => $values));
    }

}
