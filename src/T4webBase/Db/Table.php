<?php

namespace T4webBase\Db;

class Table implements TableGatewayInterface {
    
    private $tableGateway;
    private $primaryKey;

    public function __construct(TableGateway $tableGateway, $primaryKey) {
        $this->tableGateway = $tableGateway;
        $this->primaryKey = $primaryKey;
    }
    
    public function getName() {
        return $this->tableGateway->getTable();
    }
    
    public function insert(array $data) {
        return $this->tableGateway->insert($data);
    }
    
    public function getLastInsertId() {
        return $this->tableGateway->getLastInsertValue();
    }
    
    public function selectMany(Select $select) {
        $resultSet = $this->tableGateway->selectWith($select->getZendSelect());
        
        return $resultSet->toArray();
    }
    
    public function selectOne(Select $select) {
        $select->limit(1)
                ->offset(0);
        
        $resultSet = $this->tableGateway->selectWith($select->getZendSelect());
        $result = $resultSet->toArray();
        
        return isset($result[0]) ? $result[0] : array();
    }

    // TODO: must refactor
    public function count(Select $select) {
        $select->reset('limit');
        $select->reset('offset');
        $select->reset('order');

        $field = $select->getZendSelect()->getRawState('columns')[0];
        $select->getZendSelect()->columns(array('count' => new \Zend\Db\Sql\Expression("COUNT($field)")));
        $resultSet = $this->selectMany($select);
        if (empty($resultSet)) {
            return 0;
        }

        if (count($resultSet) > 1) {
            return count($resultSet);
        }

        return $resultSet[0]['count'];
    }
    
    public function updateByPrimaryKey(array $data, $primaryKeyValue) {
        return $this->tableGateway->update($data, array("$this->primaryKey = $primaryKeyValue"));
    }

    public function updateByAttribute(array $data, $attributeName, $primaryKeyValue) {
        if (!is_array($primaryKeyValue)) {
            $primaryKeyValue = array($primaryKeyValue);
        }
        $where = "$attributeName IN (" . implode(',', $primaryKeyValue) . ")";

        return $this->tableGateway->update($data, array($where));
    }
    
    public function deleteByPrimaryKey($primaryKeyValue) {
        return $this->tableGateway->delete(array("$this->primaryKey = $primaryKeyValue"));
    }

    public function deleteByAttribute($attributeName, $primaryKeyValue) {
        if (!is_array($primaryKeyValue)) {
            $primaryKeyValue = array($primaryKeyValue);
        }
        $where = "$attributeName IN (" . implode(',', $primaryKeyValue) . ")";

        return $this->tableGateway->delete(array($where));
    }
    
    public function delete($where) {
        return $this->tableGateway->delete($where);
    }
}
