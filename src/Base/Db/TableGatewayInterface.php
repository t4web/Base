<?php

namespace Base\Db;

interface TableGatewayInterface {
    
    public function insert(array $data);
    
    public function getLastInsertId();
    
    public function selectMany(Select $select);
    
    public function selectOne(Select $select);
    
    public function count(Select $select);
    
    public function updateByPrimaryKey(array $data, $primaryKeyValue);

    public function updateByAttribute(array $data, $attributeName, $primaryKeyValue);
    
    public function deleteByPrimaryKey($primaryKeyValue);

    public function deleteByAttribute($attributeName, $primaryKeyValue);
    
    public function delete($where);
}
