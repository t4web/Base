<?php

namespace BaseTest\Domain\Criteria;

use Base\Domain\Criteria\Factory;
use Base\Domain\Criteria\CompositeCriteria;
use Base\Domain\Criteria\EmptyCriteria as NewEmptyCriteria;
use Base\Domain\Criteria\NewCriteria;
use BaseTest\Domain\Assets\Criteria\EmptyCriteria as AssetEmptyCriteria;
use BaseTest\Domain\Assets\Criteria\Id as AssetIdCriteria;

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
        
        $factory = new Factory('BaseTest\Domain', 'Assets', array(), array());
        $result = $factory->create($params);
        
        $this->assertEquals($compositeCriteria, $result);
    }
    
    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Criteria class BaseTest\Domain\Criteria\EmptyCriteria not found
     */
    public function testCreateNotFoundEmptyCriteria() {
        $params = array();
        
        $factory = new Factory('BaseTest', 'Domain', array(), array());
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
        $params = array('BaseTest\Domain' => array('Assets' => array('id' => 1)));
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
        $factory = new Factory('BaseTest\Domain', 'Assets', array(), $criteries);
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
        $factory = new Factory('BaseTest', 'Domain', array(), $criteries);
        $factory->create($params);
    }
    
    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Dependency table for module BaseTest\Domain entity Assets not found
     */
    public function testCreateNotFoundDependenciesTable() {
        $params = array('BaseTest\Domain' => array('Assets' => array('id' => 1)));
        
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
        $factory = new Factory('BaseTest', 'Domain', array(), $criteries);
        $factory->create($params);
    }
    
    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Dependency rule for module BaseTest\Domain entity Assets not found
     */
    public function testCreateNotFoundDependenciesTableRule() {
        $params = array('BaseTest\Domain' => array('Assets' => array('id' => 1)));
        
        $dependencies = array(
            'Domain' => array(
                'BaseTest\Domain' => array(
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
        $factory = new Factory('BaseTest', 'Domain', $dependencies, $criteries);
        $factory->create($params);
    }
    
    public function testCreateWithDependencies() {
        $params = array('BaseTest\Domain' => array('Assets' => array('id' => 1)));
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
                'BaseTest\Domain' => array(
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
        $factory = new Factory('BaseTest', 'Domain', $dependencies, $criteries);
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

        $factory = new Factory('BaseTest\Domain', 'Assets', array(), $criteries);

        $this->assertInstanceOf($criteriaClass, $factory->getNativeCriteria($criteriaName, 1));
    }

    public function DataProvider() {
        return array(
            array('name', 'Base\Domain\Criteria\NewCriteria'),
            array('productId', 'Base\Domain\Criteria\NewCriteria'),
            array('Id', 'BaseTest\Domain\Assets\Criteria\Id')
        );
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Criteria class BaseTest\Domain\Criteria\UnknownCriteria not found
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

        $factory = new Factory('BaseTest', 'Domain', array(), $criteries);

        $factory->getNativeCriteria('UnknownCriteria', 1);
    }
}
