<?php

namespace T4webBase\Domain\Repository;

use Zend\EventManager\EventManagerInterface;
use T4webBase\Domain\Mapper\DbMapperInterface;
use T4webBase\Db\TableGatewayInterface;
use T4webBase\Db\QueryBuilderInterface;
use T4webBase\Domain\Criteria\CriteriaInterface;
use T4webBase\Domain\EntityInterface;

class DbRepository {

    /**
     * @var DbMapperInterface
     */
    protected $dbMapper;

    /**
     * @var TableGatewayInterface
     */
    protected $tableGateway;

    /**
     * @var QueryBuilderInterface
     */
    protected $queryBuilder;

    /**
     * @var IdentityMap
     */
    protected $identityMap;

    /**
     * @var EventManagerInterface
     */
    protected $eventManager;

    /**
     * @var EntityChangedEvent
     */
    protected $event;

    public function __construct(
            TableGatewayInterface $tableGateway,
            DbMapperInterface $dbMapper,
            QueryBuilderInterface $queryBuilder,
            IdentityMap $identityMap,
            EventManagerInterface $eventManager) {
        
        $this->tableGateway = $tableGateway;
        $this->dbMapper = $dbMapper;
        $this->queryBuilder = $queryBuilder;
        $this->identityMap = $identityMap;
        $this->eventManager = $eventManager;
    }

    /**
     * @deprecated use constructor for this
     */
    public function setIdentityMap(IdentityMap $identityMap) {
        $this->identityMap = $identityMap;
        return $this;
    }
    
    public function getIdentityMap() {
        return $this->identityMap;
    }
    
    /**
     * @param \T4webBase\Domain\Criteria\CriteriaInterface $criteria
     * @return \T4webBase\Domain\Collection
     */
    public function findMany(CriteriaInterface $criteria) {
        $criteria->build($this->queryBuilder);
        
        $select = $this->queryBuilder->getQuery();
        
        $rows = $this->tableGateway->selectMany($select);
        
        $collection = $this->dbMapper->fromTableRows($rows);
        
        foreach ($collection as $entity) {
            $this->toIdentityMap($entity);
        }
        
        return $collection;
    }
    
    /**
     * @param CriteriaInterface $criteria
     * @param array $columns
     * @return array
     */
    public function findManyByColumns(CriteriaInterface $criteria, array $columns) {
        if (!array_search('id', $columns)) {
            $columns[] = 'id';
        }
        foreach ($columns as $column) {
            $this->queryBuilder->addColumn($column);
        }

        $criteria->build($this->queryBuilder);

        $select = $this->queryBuilder->getQuery();
        $rows = $this->tableGateway->selectMany($select);

        return $rows;
    }
    
    /**
     * @param \T4webBase\Domain\Criteria\CriteriaInterface $criteria
     * @return \T4webBase\Domain\EntityInterface
     */
    public function find(CriteriaInterface $criteria) {
        $criteria->build($this->queryBuilder);
        
        $select = $this->queryBuilder->getQuery();
        
        $row = $this->tableGateway->selectOne($select);
        if (empty($row)) {
            return null;
        }
        
        $entity = $this->dbMapper->fromTableRow($row);
        
        $this->toIdentityMap($entity);
        
        return $entity;
    }
    
    public function count(CriteriaInterface $criteria) {
        $criteria->build($this->queryBuilder);
        
        $select = $this->queryBuilder->getQuery();
        
        return $this->tableGateway->count($select);
    }
    
    public function add(EntityInterface $entity) {
        $data = $this->dbMapper->toTableRow($entity);
        
        $id = $entity->getId();

        if ($this->getIdentityMap()->offsetExists((int)$id)) {
            if (!$this->isEntityChanged($entity)) {
                return;
            }
            $result = $this->tableGateway->updateByPrimaryKey($data, $id);

            $e = $this->getEvent();
            $originalEntity = $this->getIdentityMap()->offsetGet($entity->getId());
            $e->setOriginalEntity($originalEntity);
            $e->setChangedEntity($entity);

            $this->triggerChanges($e);
            $this->triggerAttributesChange($e);

            return $result;
        } else {
            $this->tableGateway->insert($data);
        
            if (empty($id)) {
                $id = $this->tableGateway->getLastInsertId();
                $entity->populate(compact('id'));
            }

            $this->toIdentityMap($entity);
        }
    }
    
    public function delete(EntityInterface $entity) {
        $id = $entity->getId();
        
        if (!empty($id)) {
            return $this->tableGateway->deleteByPrimaryKey($id);
        }
        
        return false;
    }

    public function deleteByAttribute($attributeValue, $attributeName = 'id') {
        if (empty($attributeValue)) {
            return false;
        }
        return $this->tableGateway->deleteByAttribute($attributeName, $attributeValue);
    }

    public function updateByAttribute($data, $attributeValue, $attributeName = 'id') {
        if (empty($attributeValue)) {
            return false;
        }
        return $this->tableGateway->updateByAttribute($data, $attributeName, $attributeValue);
    }

    private function isEntityChanged(EntityInterface $changedEntity) {
        $originalEntity = $this->getIdentityMap()->offsetGet($changedEntity->getId());
        return $changedEntity != $originalEntity;
    }

    private function triggerChanges(EntityChangedEvent $e) {
        $changedEntity = $e->getChangedEntity();
        $this->eventManager->trigger($this->getEntityChangeEventName($changedEntity), $this, $e);
    }

    private function triggerAttributesChange(EntityChangedEvent $e) {
        $changedEntity = $e->getChangedEntity();

        $originalAttrs = $e->getOriginalEntity()->extract();
        $changedAttrs = $changedEntity->extract();

        foreach (array_keys(array_diff($originalAttrs, $changedAttrs)) as $attribute) {
            $this->eventManager->trigger($this->getAttributeChangeEventName($changedEntity, $attribute), $this, $e);
        }
    }

    private function getEntityChangeEventName(EntityInterface $changedEntity) {
        return sprintf('entity:%s:changed', get_class($changedEntity));
    }

    private function getAttributeChangeEventName(EntityInterface $changedEntity, $attributeName) {
        return sprintf('attribute:%s:%s:changed', get_class($changedEntity), $attributeName);
    }

    /**
     * @return EntityChangedEvent
     */
    private function getEvent() {
        if (null === $this->event) {
            $this->event = new EntityChangedEvent();
            $this->event->setTarget($this);
        }
        return $this->event;
    }
    
    private function toIdentityMap(EntityInterface $entity) {
        $this->getIdentityMap()->offsetSet($entity->getId(), $entity);
    }
}
