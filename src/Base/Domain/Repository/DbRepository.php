<?php

namespace Base\Domain\Repository;

use Base\Domain\Mapper\DbMapperInterface;
use Base\Db\TableGatewayInterface;
use Base\Db\QueryBuilderInterface;
use Base\Domain\Criteria\CriteriaInterface;
use Base\Domain\EntityInterface;

class DbRepository {
    
    protected $dbMapper;
    protected $tableGateway;
    protected $queryBuilder;
    protected $identityMap;

    public function __construct(
            TableGatewayInterface $tableGateway,
            DbMapperInterface $dbMapper,
            QueryBuilderInterface $queryBuilder) {
        
        $this->tableGateway = $tableGateway;
        $this->dbMapper = $dbMapper;
        $this->queryBuilder = $queryBuilder;
    }

    public function setIdentityMap(IdentityMap $identityMap) {
        $this->identityMap = $identityMap;
        return $this;
    }
    
    public function getIdentityMap() {
        if (is_null($this->identityMap)) {
            $this->identityMap = new IdentityMap();
        }
        return $this->identityMap;
    }
    
    /**
     * @param \Base\Domain\Criteria\CriteriaInterface $criteria
     * @return \Base\Domain\Collection
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
     * @param \Base\Domain\Criteria\CriteriaInterface $criteria
     * @return \Base\Domain\EntityInterface
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
            return $this->tableGateway->updateByPrimaryKey($data, $id);
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
    
    private function toIdentityMap(EntityInterface $entity) {
        $this->getIdentityMap()->offsetSet($entity->getId(), $entity);
    }
}
