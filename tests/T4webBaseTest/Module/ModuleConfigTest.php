<?php

namespace T4webBaseTest\Module;

use T4webBase\Module\ModuleConfig;

class ModuleConfigTest extends \PHPUnit_Framework_TestCase {
    
    public function testConstructor() {
        $config = array('foo' => 'bar');
        $moduleConfig = new ModuleConfig($config);
        
        $this->assertAttributeEquals($config, 'config', $moduleConfig);
    }
    
    public function testGetDbDependencies() {
        $dependencies = array('foo', 'bar');

        $config = array(
            'db' => array(
                'dependencies' => $dependencies
            )
        );
        
        $moduleConfig = new ModuleConfig($config);
        
        $this->assertEquals($dependencies, $moduleConfig->getDbDependencies());
    }

    public function testGetDbDependenciesNotSet() {
        $config = array();

        $moduleConfig = new ModuleConfig($config);

        $this->assertEquals(array(), $moduleConfig->getDbDependencies());
    }

    public function testGetCriteriesReturnEmpty() {
        $criteries = array();
        $config = array();

        $moduleConfig = new ModuleConfig($config);

        $this->assertEquals($criteries, $moduleConfig->getCriteries());
    }
    
    public function testGetCriteries() {
        $criteries = array(
            'EntityName' => array(
                'empty' => array(
                    'table' => 'table',
                ),
                'id' => array(
                    'field' => 'field',
                    'table' => 'table',
                    'buildMethod' => 'buildMethod',
                ),
            )
        );
        
        $config = array(
            'criteries' => $criteries
        );
        
        $moduleConfig = new ModuleConfig($config);
        
        $this->assertEquals($criteries, $moduleConfig->getCriteries());
    }

    public function testGetDbTablePrimaryKey() {
        $tableAlias = 'users';
        $pk = 'user_id';

        $config = array(
            'db' => array(
                'tables' => array(
                    $tableAlias => array('pk' => $pk)
                )
            )
        );

        $moduleConfig = new ModuleConfig($config);

        $this->assertEquals($pk, $moduleConfig->getDbTablePrimaryKey($tableAlias));
    }

    public function testGetDbTablePrimaryKeyNotSet() {
        $tableAlias = 'users';
        $config = array();

        $moduleConfig = new ModuleConfig($config);

        $this->assertEquals('id', $moduleConfig->getDbTablePrimaryKey($tableAlias));
    }
    
    public function testGetDbTableColumnsAsAttributesMap() {
        $columnsAsAttributesMap = array('foo' => 'bar');
        
        $config = array(
            'db' => array(
                'tables' => array(
                    'table_alias' => array(
                        'columnsAsAttributesMap' => $columnsAsAttributesMap,
                    ),
                ),
            ),
        );
        
        $moduleConfig = new ModuleConfig($config);
        
        $this->assertEquals($columnsAsAttributesMap, $moduleConfig->getDbTableColumnsAsAttributesMap('table_alias'));
    }
    
    public function testGetDbTableColumnsAsAttributesMapThrowExceptionNotFound() {
        $config = array(
            'db' => array(
                'tables' => array(
                    'table_alias' => array(
                        'name' => 'table_name',
                    ),
                ),
            ),
        );
        
        $this->setExpectedException('UnexpectedValueException', "Not found columns as attributes map for table with alias 'wrong_table_alias'");
        
        $moduleConfig = new ModuleConfig($config);
        $moduleConfig->getDbTableColumnsAsAttributesMap('table_alias');
    }
    
    public function testGetDbTableName() {
        $config = array(
            'db' => array(
                'tables' => array(
                    'table_alias' => array(
                        'name' => 'table_name',
                    ),
                    'table_alias_2' => array(
                        'name' => 'table_name_2',
                    ),
                ),
            ),
        );
        
        $moduleConfig = new ModuleConfig($config);
        
        $this->assertEquals('table_name', $moduleConfig->getDbTableName('table_alias'));
        $this->assertEquals('table_name_2', $moduleConfig->getDbTableName('table_alias_2'));
    }
    
    public function testGetDbTableNameThrowExceptionNotFound() {
        $config = array(
            'db' => array(
                'tables' => array(
                    'table_alias' => array(
                        'name' => 'table_name',
                    ),
                ),
            ),
        );
        
        $this->setExpectedException('UnexpectedValueException', "Not found table name with alias 'wrong_table_alias'");
        
        $moduleConfig = new ModuleConfig($config);
        $moduleConfig->getDbTableName('wrong_table_alias');
    }
}
