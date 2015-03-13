<?php

namespace T4webBaseTest\Domain;

use T4webBase\Domain\CollectionWithName;
use T4webBase\Domain\Entity;
use T4webBaseTest\Domain\Assets\EntityWithName;

class CollectionWithNameTest extends \PHPUnit_Framework_TestCase {
    
    public function testEmptyCollectionReturnEmptyArray() {
        $expectedNames = array();
        
        $collection = new CollectionWithName();
        
        $this->assertSame($expectedNames, $collection->getNamesForSelect());
    }
    
    public function testCollectionReturnArrayCategoriesName() {
        $expectedNames = array(
            1 => 'one'
        );
        
        $model = new EntityWithName(array('name' => 'one'));
        
        $collection = new CollectionWithName();
        $collection->offsetSet(1, $model);
        
        $this->assertSame($expectedNames, $collection->getNamesForSelect());
    }
    
    /**
     * @expectedException \RuntimeException
     */
    public function testForException(){
        $model = new Entity();
        
        $collection = new CollectionWithName();
        $collection->append($model);
        
        $collection->getNamesForSelect();
    }
}
