<?php

namespace Base\Domain\Service;

use Base\InputFilter\InputFilterInterface;
use Base\Domain\Repository\DbRepository;
use Base\Domain\Criteria\Factory as CriteriaFactory;
use Zend\EventManager\EventManager;
use Base\Domain\EntityInterface;
use Zend\Filter\Word\CamelCaseToUnderscore;

abstract class UpdateAbstract implements UpdateInterface {
    
    protected $inputFilter;
    /**
     *
     * @var \Base\Domain\Repository\DbRepository
     */
    protected $repository;
    /**
     *
     * @var \Base\Domain\Criteria\Factory
     */
    protected $criteriaFactory;
    protected $eventManager;
    protected $filterCamelCaseToUnderscore;

    public function __construct(
            InputFilterInterface $inputFilter,
            DbRepository $repository,
            CriteriaFactory $criteriaFactory,
            EventManager $eventManager = null) {
        
        $this->inputFilter = $inputFilter;
        $this->repository = $repository;
        $this->criteriaFactory = $criteriaFactory;
        $this->eventManager = $eventManager;
        $this->filterCamelCaseToUnderscore = new CamelCaseToUnderscore();
    }
    
    public function isValid(array $data) {
        $this->inputFilter->setData($data);
        return $this->inputFilter->isValid();
    }
    
    abstract public function update($id, array $data);

    protected function trigger($event, EntityInterface $entity) {
        if (!$this->eventManager) {
            return;
        }

        $this->eventManager->trigger($event, $this, compact('entity'));
    }

    public function activate($id) {
        /*@var $entity \Base\Domain\Entity */
        $entity = $this->repository->find($this->criteriaFactory->getNativeCriteria('Id', $id));
        if (!$entity) {
            return false;
        }
        
        $entity->setActivated();
        
        $this->repository->add($entity);
        $this->trigger('activate:post', $entity);
        
        return true;
    }
    
    public function inactivate($id) {
        /*@var $entity \Base\Domain\Entity */
        $entity = $this->repository->find($this->criteriaFactory->getNativeCriteria('Id', $id));
        if (!$entity) {
            return false;
        }
        
        $entity->setInactivated();
        
        $this->repository->add($entity);
        $this->trigger('inactivate:post', $entity);

        return true;
    }
    
    public function delete($id) {
        /*@var $entity \Base\Domain\Entity */
        $entity = $this->repository->find($this->criteriaFactory->getNativeCriteria('Id', $id));
        if (!$entity) {
            return false;
        }
        
        $entity->setDeleted();
        
        $this->repository->add($entity);
        $this->trigger('delete:post', $entity);

        return true;
    }
    
    public function getMessages() {
        return $this->inputFilter->getMessages();
    }
    
    public function getValues() {
        return $this->inputFilter->getValues();
    }

    public function updateAll($attributeValue, $attributeName = 'id', array $data) {
        $criteria = $this->criteriaFactory->getNativeCriteria($attributeName, $attributeValue);
        $collection = $this->repository->findMany($criteria);
        if (!$collection->count()) {
            return false;
        }

        $this->repository->updateByAttribute($data, $attributeValue, $criteria->getField());

        return $collection;

    }
}
