<?php

namespace Base\Domain\Service;

use Base\InputFilter\InputFilterInterface;
use Base\Domain\Repository\DbRepository;
use Base\Domain\Factory\EntityFactoryInterface;
use Zend\EventManager\EventManager;
use Base\Domain\EntityInterface;

class Create implements CreateInterface {
    
    protected $inputFilter;
    protected $repository;
    protected $entityFactory;
    protected $eventManager;

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
    
    public function isValid(array $data) {
        $this->inputFilter->setData($data);
        return $this->inputFilter->isValid();
    }
    
    public function create($id = null) {
        $data = $this->inputFilter->getValues();
        
        if (empty($data)) {
            throw new \RuntimeException("Cannot create entity form empty data");
        }
        
        $entity = $this->entityFactory->create($data, $id);
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
    
    public function getMessages() {
        return $this->inputFilter->getMessages();
    }
    
    public function getValues() {
        return $this->inputFilter->getValues();
    }
}
