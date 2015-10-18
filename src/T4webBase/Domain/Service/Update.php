<?php

namespace T4webBase\Domain\Service;

use Zend\EventManager\EventManager;
use Zend\InputFilter\InputFilterInterface;
use T4webBase\InputFilter\ErrorAwareTrait;
use T4webBase\Domain\Repository\DbRepository;
use T4webBase\Domain\Criteria\Factory as CriteriaFactory;
use T4webBase\Domain\EntityInterface;

class Update implements UpdateInterface {
    use ErrorAwareTrait;

    /**
     * @var InputFilterInterface
     */
    protected $inputFilter;

    /**
     * @var \T4webBase\Domain\Repository\DbRepository
     */
    protected $repository;

    /**
     * @var \T4webBase\Domain\Criteria\Factory
     */
    protected $criteriaFactory;

    /**
     * @var EventManager
     */
    protected $eventManager;

    public function __construct(
        InputFilterInterface $inputFilter,
        DbRepository $repository,
        CriteriaFactory $criteriaFactory,
        EventManager $eventManager = null) {

        $this->inputFilter = $inputFilter;
        $this->repository = $repository;
        $this->criteriaFactory = $criteriaFactory;
        $this->eventManager = $eventManager;
    }

    public function isValid(array $data) {
        $this->inputFilter->setData($data);
        if (!$this->inputFilter->isValid()) {
            $this->setErrors($this->inputFilter->getMessages());
            return false;
        }
        return true;
    }

    /**
     * @param $id
     * @param array $data
     * @return EntityInterface|void
     */
    public function update($id, array $data) {
        if (!$this->isValid($data)) {
            return;
        }

        $data = $this->inputFilter->getValues();

        /** @var EntityInterface $entity */
        $entity = $this->repository->find($this->criteriaFactory->getNativeCriteria('Id', $id));

        $entity->populate($data);

        $this->repository->add($entity);
        $this->trigger('update:post', $entity);

        return $entity;
    }

    /**
     * @param string $event
     */
    protected function trigger($event, EntityInterface $entity) {
        if (!$this->eventManager) {
            return;
        }

        $this->eventManager->trigger($event, $this, compact('entity'));
    }

    public function activate($id) {
        /** @var $entity \T4webBase\Domain\Entity */
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
        /** @var $entity \T4webBase\Domain\Entity */
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
        /** @var $entity \T4webBase\Domain\Entity */
        $entity = $this->repository->find($this->criteriaFactory->getNativeCriteria('Id', $id));
        if (!$entity) {
            return false;
        }

        $entity->setDeleted();

        $this->repository->add($entity);
        $this->trigger('delete:post', $entity);

        return true;
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
