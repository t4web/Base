<?php

namespace T4webBaseTest\Domain\Criteria;

use T4webBase\Domain\Criteria\Factory;
use T4webBase\Domain\Criteria\CompositeCriteria;
use T4webBase\Domain\Criteria\EmptyCriteria as NewEmptyCriteria;
use T4webBase\Domain\Criteria\NewCriteria;
use T4webBaseTest\Domain\Assets\Criteria\EmptyCriteria as AssetEmptyCriteria;
use T4webBaseTest\Domain\Assets\Criteria\Id as AssetIdCriteria;

class FactoryTest extends \PHPUnit_Framework_TestCase {
    
    public function testCreateReturnCompositeCriteriaWithEmptyNewCriteria() {
        $params = array();
        $criteria = new NewEmptyCriteria(null, null, 'table', null);
        $compositeCriteria = new CompositeCriteria();
        $compositeCriteria->add($criteria);
        
        $criteries = array(
            'entityName' => array(
                'empty' => array(
                    'table' => 'table',
                ),
            ),
        );
        $factory = new Factory('moduleName', 'entityName', array(), $criteries);
        $result = $factory->create($params);
        
        $this->assertEquals($compositeCriteria, $result);
    }
    
    public function testCreateReturnCompositeCriteriaWithEmptyCriteria() {
        $params = array();
        $criteria = new AssetEmptyCriteria();
        $compositeCriteria = new CompositeCriteria();
        $compositeCriteria->add($criteria);
        
        $factory = new Factory('T4webBaseTest\Domain', 'Assets', array(), array());
        $result = $factory->create($params);
        
        $this->assertEquals($compositeCriteria, $result);
    }
    
    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Criteria class T4webBaseTest\Domain\Criteria\EmptyCriteria not found
     */
    public function testCreateNotFoundEmptyCriteria() {
        $params = array();
        
        $factory = new Factory('T4webBaseTest', 'Domain', array(), array());
        $factory->create($params);
    }
    
    public function testCreateReturnCompositeCriteriaWithEmptyCriteries() {
        $params = array('moduleName' => array('entityName' => array('id' => null)));
        $criteria1 = new NewEmptyCriteria(null, null, 'table', null);
        $criteria2 = new NewEmptyCriteria(null, null, 'table', null);
        $compositeCriteria = new CompositeCriteria();
        $compositeCriteria->add($criteria1);
        $compositeCriteria->add($criteria2);
        
        $criteries = array(
            'entityName' => array(
                'empty' => array(
                    'table' => 'table',
                ),
            ),
        );
        $factory = new Factory('moduleName', 'entityName', array(), $criteries);
        $result = $factory->create($params);
        
        $this->assertEquals($compositeCriteria, $result);
    }
    
    public function testCreateReturnCompositeCriteriaWithNewIdCriteria() {
        $params = array('moduleName' => array('entityName' => array('id' => 1, 'status' => 2)));
        $criteria1 = new NewEmptyCriteria(null, null, 'table', null);
        $criteria2 = new NewCriteria(1, 'field', 'table', 'buildMethod');
        $criteria3 = new NewCriteria(2, null, 'table', 'buildMethod');
        $compositeCriteria = new CompositeCriteria();
        $compositeCriteria->add($criteria1);
        $compositeCriteria->add($criteria2);
        $compositeCriteria->add($criteria3);

        $criteries = array(
            'entityName' => array(
                'empty' => array(
                    'table' => 'table',
                ),
                'id' => array(
                    'field' => 'field',
                    'table' => 'table',
                    'buildMethod' => 'buildMethod',
                ),
                'status' => array(
                    'table' => 'table',
                    'buildMethod' => 'buildMethod',
                ),
            ),
        );
        $factory = new Factory('moduleName', 'entityName', array(), $criteries);
        $result = $factory->create($params);
        
        $this->assertEquals($compositeCriteria, $result);
    }
    
    public function testCreateReturnCompositeCriteriaWithIdCriteria() {
        $params = array('T4webBaseTest\Domain' => array('Assets' => array('id' => 1)));
        $criteria1 = new NewEmptyCriteria(null, null, 'table', null);
        $criteria2 = new AssetIdCriteria(1);
        $compositeCriteria = new CompositeCriteria();
        $compositeCriteria->add($criteria1);
        $compositeCriteria->add($criteria2);
        
        $criteries = array(
            'Assets' => array(
                'empty' => array(
                    'table' => 'table',
                ),
            ),
        );
        $factory = new Factory('T4webBaseTest\Domain', 'Assets', array(), $criteries);
        $result = $factory->create($params);
        
        $this->assertEquals($compositeCriteria, $result);
    }
    
    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Criteria class ModuleName\EntityName\Criteria\Id not found
     */
    public function testCreateNotFoundIdCriteria() {
        $params = array('moduleName' => array('entityName' => array('id' => 1)));
        
        $criteries = array(
            'Domain' => array(
                'empty' => array(
                    'table' => 'table',
                ),
            ),
        );
        $factory = new Factory('T4webBaseTest', 'Domain', array(), $criteries);
        $factory->create($params);
    }
    
    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Dependency table for module T4webBaseTest\Domain entity Assets not found
     */
    public function testCreateNotFoundDependenciesTable() {
        $params = array('T4webBaseTest\Domain' => array('Assets' => array('id' => 1)));
        
        $criteries = array(
            'Domain' => array(
                'empty' => array(
                    'table' => 'table',
                ),
                'id' => array(
                    'field' => 'field',
                    'table' => 'table',
                    'buildMethod' => 'buildMethod',
                ),
            ),
        );
        $factory = new Factory('T4webBaseTest', 'Domain', array(), $criteries);
        $factory->create($params);
    }
    
    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Dependency rule for module T4webBaseTest\Domain entity Assets not found
     */
    public function testCreateNotFoundDependenciesTableRule() {
        $params = array('T4webBaseTest\Domain' => array('Assets' => array('id' => 1)));
        
        $dependencies = array(
            'Domain' => array(
                'T4webBaseTest\Domain' => array(
                    'Assets' => array(
                        'table' => 'table',
                    ),
                ),
            ),
        );
        $criteries = array(
            'Domain' => array(
                'empty' => array(
                    'table' => 'table',
                ),
                'id' => array(
                    'field' => 'field',
                    'table' => 'table',
                    'buildMethod' => 'buildMethod',
                ),
            ),
        );
        $factory = new Factory('T4webBaseTest', 'Domain', $dependencies, $criteries);
        $factory->create($params);
    }
    
    public function testCreateWithDependencies() {
        $params = array('T4webBaseTest\Domain' => array('Assets' => array('id' => 1)));
        $criteria1 = new NewEmptyCriteria(null, null, 'table', null);
        $criteria2 = new AssetIdCriteria(1);
        $criteria2->setAsForeign();
        $criteria2->setJoinTable('table');
        $criteria2->setJoinRule('rule');
        $compositeCriteria = new CompositeCriteria();
        $compositeCriteria->add($criteria1);
        $compositeCriteria->add($criteria2);
        
        $dependencies = array(
            'Domain' => array(
                'T4webBaseTest\Domain' => array(
                    'Assets' => array(
                        'table' => 'table',
                        'rule' => 'rule',
                    ),
                ),
            ),
        );
        $criteries = array(
            'Domain' => array(
                'empty' => array(
                    'table' => 'table',
                ),
            ),
        );
        $factory = new Factory('T4webBaseTest', 'Domain', $dependencies, $criteries);
        $result = $factory->create($params);
        
        $this->assertEquals($compositeCriteria, $result);
    }

    /**
     * @dataProvider DataProvider
     */
    public function testGetNativeCriteria($criteriaName, $criteriaClass) {
        $criteries = array(
            'Assets' => array(
                'empty' => array(
                    'table' => 'table',
                ),
                'name' => array(
                    'table' => 'table',
                    'buildMethod' => 'buildMethod',
                ),
                'productId' => array(
                    'field' => 'field1',
                    'table' => 'table1',
                    'buildMethod' => 'buildMethod1',
                ),
            ),
        );

        $factory = new Factory('T4webBaseTest\Domain', 'Assets', array(), $criteries);

        $this->assertInstanceOf($criteriaClass, $factory->getNativeCriteria($criteriaName, 1));
    }

    public function DataProvider() {
        return array(
            array('name', 'T4webBase\Domain\Criteria\NewCriteria'),
            array('productId', 'T4webBase\Domain\Criteria\NewCriteria'),
            array('Id', 'T4webBaseTest\Domain\Assets\Criteria\Id')
        );
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Criteria class T4webBaseTest\Domain\Criteria\UnknownCriteria not found
     */
    public function testGetNativeCriteriaBadCriteria() {
        $criteries = array(
            'Domain' => array(
                'empty' => array(
                    'table' => 'table',
                ),
                'id' => array(
                    'field' => 'field',
                    'table' => 'table',
                    'buildMethod' => 'buildMethod',
                ),
                'productId' => array(
                    'field' => 'field1',
                    'table' => 'table1',
                    'buildMethod' => 'buildMethod1',
                ),
            ),
        );

        $factory = new Factory('T4webBaseTest', 'Domain', array(), $criteries);

        $factory->getNativeCriteria('UnknownCriteria', 1);
    }
}
