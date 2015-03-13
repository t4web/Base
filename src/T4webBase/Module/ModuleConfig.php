<?php

namespace T4webBase\Module;

class ModuleConfig {
    
    private $config;
    
    public function __construct(array $config) {
        $this->config = $config;
    }
    
    public function getDbDependencies() {
        
        if (!isset($this->config['db']['dependencies'])) {
            return array();
        }
        
        return $this->config['db']['dependencies'];
    }
    
    public function getCriteries() {
        $result = array();
        if (isset($this->config['criteries'])) {
            $result = $this->config['criteries'];
        }
        
        return $result;
    }
    
    public function getDbTablePrimaryKey($tableAlias) {
        
        if (!isset($this->config['db']['tables'][$tableAlias]['pk'])) {
            return 'id';
        }
        
        return $this->config['db']['tables'][$tableAlias]['pk'];
    }

    public function getSizes($sizeAlias) {
        $sizes = array();
        if (isset($this->config['sizes'][$sizeAlias])) {
            $sizes = $this->config['sizes'][$sizeAlias];
        }
        
        return $sizes;
    }
    
    public function getDbTableColumnsAsAttributesMap($tableAlias) {
        
        if (!isset($this->config['db']['tables'][$tableAlias]['columnsAsAttributesMap'])) {
            throw new \UnexpectedValueException("Not found columns as attributes map for table with alias 'wrong_table_alias'");
        }
        
        return $this->config['db']['tables'][$tableAlias]['columnsAsAttributesMap'];
    }
    
    public function getDbTableName($tableAlias) {
        
        if (!isset($this->config['db']['tables'][$tableAlias]['name'])) {
            throw new \UnexpectedValueException("Not found table name with alias '$tableAlias'");
        }
        
        return $this->config['db']['tables'][$tableAlias]['name'];
    }

}