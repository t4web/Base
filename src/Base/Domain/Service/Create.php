<?php

namespace Base\Domain\Service;

use Zend\EventManager\EventManager;
use Base\InputFilter\InputFilterInterface;
use Base\InputFilter\InvalidInputError;
use Base\Domain\Repository\DbRepository;
use Base\Domain\Factory\EntityFactoryInterface;
use Base\Domain\EntityInterface;

class Create implements CreateInterface {

    /**
     * @var InputFilterInterface
     */
    protected $inputFilter;

    /**
     * @var DbRepository
     */
    protected $repository;

    /**
     * @var EntityFactoryInterface
     */
    protected $entityFactory;

    /**
     * @var EventManager
     */
    protected $eventManager;

    /**
     * @var InvalidInputError
     */
    protected $errors;

    public function __construct(
            InputFilterInterface $inputFilter,
            DbRepository $repository,
            EntityFactoryInterface $entityFactory,
            EventManager $eventManager = null) {
        
        $this->inputFilter = $inputFilter;
        $this->repository = $repository;
        $this->entityFactory = $entityFactory;
        $this->eventManager = $eventManager;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function isValid(array $data) {
        $this->inputFilter->setData($data);

        if (!$this->inputFilter->isValid()) {
            $this->errors = new InvalidInputError($this->inputFilter->getMessages());
            return false;
        }
        return true;
    }

    /**
     * @param array $data
     * @return EntityInterface|null
     */
    public function create(array $data) {

        if (!$this->isValid($data)) {
            return;
        }

        $data = $this->inputFilter->getValues();

        if (empty($data)) {
            throw new \RuntimeException("Cannot create entity form empty data");
        }
        
        $entity = $this->entityFactory->create($data);
        $this->repository->add($entity);

        $this->trigger($entity);

        return $entity;
    }

    protected function trigger(EntityInterface $entity) {
        if (!$this->eventManager) {
            return;
        }

        $this->eventManager->trigger('create:post', $this, compact('entity'));
    }
    
    public function getErrors() {
        return $this->errors;
    }
    
    public function getValues() {
        return $this->inputFilter->getValues();
    }
}
