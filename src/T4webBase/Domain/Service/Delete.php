<?php

namespace T4webBase\Domain\Service;

use T4webBase\Domain\Repository\DbRepository;
use T4webBase\Domain\Criteria\Factory as CriteriaFactory;
use Zend\EventManager\EventManager;
use T4webBase\Domain\Criteria\AbstractCriteria;
use T4webBase\InputFilter\ErrorAwareTrait;
use T4webBase\Domain\EntityInterface;

class Delete implements DeleteInterface {

    use ErrorAwareTrait;

    /**
     * @var EntityInterface
     */
    protected $entity;

    /**
     *
     * @var DbRepository
     */
    protected $repository;

    /**
     * @var EventManager
     */
    protected $eventManager;
    /**
     *
     * @var CriteriaFactory
     */
    protected $criteriaFactory;

    /**
     * @param DbRepository $repository
     * @param CriteriaFactory $criteriaFactory
     * @param EventManager|null $eventManager
     */
    public function __construct(
            DbRepository $repository,
            CriteriaFactory $criteriaFactory,
            EventManager $eventManager = null) {
        
        $this->repository = $repository;
        $this->criteriaFactory = $criteriaFactory;
        $this->eventManager = $eventManager;
    }

    /**
     * @return EntityInterface
     */
    public function getEntity() {
        return $this->entity;
    }

    /**
     * @param $id
     * @param string $attribyteName
     * @return bool|EntityInterface
     */
    public function delete($id, $attribyteName = 'Id') {
        $this->entity = $this->repository->find($this->criteriaFactory->getNativeCriteria($attribyteName, $id));
        if (!$this->entity) {
            $this->setErrors(array('general' => sprintf("Entity #%s does not found.", $id)));
            return false;
        }
        
        $this->repository->delete($this->entity);

        $this->trigger('delete:post', $this->entity, 'entity');
        
        return $this->entity;
    }

    public function deleteAll($attributeValue, $attributeName = 'Id') {
        /** @var $criteria AbstractCriteria */
        $criteria = $this->criteriaFactory->getNativeCriteria($attributeName, $attributeValue);
        $collection = $this->repository->findMany($criteria);
        if (!$collection->count()) {
            $this->setErrors(array('general' => 'Entities does not found.'));
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
