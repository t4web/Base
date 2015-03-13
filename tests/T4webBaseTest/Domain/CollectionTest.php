<?php

namespace T4webBaseTest\Domain;

use T4webBase\Domain\Collection;
use T4webBase\Domain\Entity;
use T4webBaseTest\Domain\Assets\EntityWithName;

class CollectionTest extends \PHPUnit_Framework_TestCase {
    
    public function testInstanceOfArrayObject() {
        $collection = new Collection();
        
        $this->assertInstanceOf('ArrayObject', $collection);
    }
    
    public function testGetIds() {
        $entity1 = new Entity();
        $entity1->populate(array('id' => 1));
        $entity2 = new Entity();
        $entity2->populate(array('id' => 2));
        
        $collection = new Collection();
        $collection->append($entity1);
        $collection->append($entity2);
        
        $this->assertEquals(array(1, 2), $collection->getIds());
    }
    
    public function testGetValuesByAttributeWithEmptyCollectionReturnArray() {
        $collection = new Collection();
        
        $this->assertEquals(array(), $collection->getValuesByAttribute('id'));
    }
    
    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Entity T4webBase\Domain\Entity has no method getDfgjksn
     */
    public function testGetValuesByAttributeWithThorwException() {
        $entity = new Entity();
        $collection = new Collection();
        $collection->append($entity);
        
        $collection->getValuesByAttribute('dfgjksn');
    }
    
    public function testGetValuesByAttribute() {
        $entity = new Entity(array('id' => 12));
        $collection = new Collection();
        $collection->append($entity);
        
        $this->assertEquals(array(12), $collection->getValuesByAttribute('id'));
    }
    
    public function testGetValuesByAttributeWithEntityId() {
        $entity = new Entity(array('id' => 12));
        $collection = new Collection();
        $collection->append($entity);
        
        $this->assertEquals(array(12 => 12), $collection->getValuesByAttribute('id', true));
    }
    
    public function testToArrayWithEmptyCollectionReturnArray() {
        $collection = new Collection();
        
        $this->assertSame(array(), $collection->toArray());
    }
    
    public function testToArray() {
        $id = 12;
        $entity = new Entity(array('id' => $id));
        $collection = new Collection();
        $collection->append($entity);
        
        $this->assertEquals(array($id => array('id' => $id)), $collection->toArray());
    }
    
    public function testToArrayEntityWithoutId() {
        $entity = new Entity();
        $collection = new Collection();
        $collection->append($entity);
        
        $this->assertEquals(array(0 => array('id' => null)), $collection->toArray());
    }

    public function testAddEmptyCollection() {
        $entity1 = new Entity(array('id' => 1));
        $collection1 = new Collection();
        $collection1->append($entity1);
        $collection2 = new Collection();

        $expectedCollection = new Collection();
        $expectedCollection->append($entity1);

        $collection1->addCollection($collection2);

        $this->assertEquals($expectedCollection, $collection1);
    }

    public function testAddCollection() {
        $entity1 = new Entity(array('id' => 1));
        $collection1 = new Collection();
        $collection1->append($entity1);
        $entity2 = new Entity(array('id' => 2));
        $collection2 = new Collection();
        $collection2->append($entity2);

        $expectedCollection = new Collection();
        $expectedCollection->append($entity1);
        $expectedCollection->append($entity2);

        $collection1->addCollection($collection2);

        $this->assertEquals($expectedCollection, $collection1);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Entity T4webBase\Domain\Entity has no method getStatus
     */
    public function testAddCollectionReturnException() {
        $entity1 = new Entity(array('id' => 1));
        $collection1 = new Collection();
        $collection1->append($entity1);
        $entity2 = new Entity(array('id' => 2));
        $collection2 = new Collection();
        $collection2->append($entity2);

        $expectedCollection = new Collection();
        $expectedCollection->append($entity1);
        $expectedCollection->append($entity2);

        $collection1->addCollection($collection2, 'status', true);

        $this->assertEquals($expectedCollection, $collection1);
    }

    public function testAddCollectionByKey() {
        $entity1 = new Entity(array('id' => 1));
        $collection1 = new Collection();
        $collection1->offsetSet($entity1->getId(), $entity1);
        $entity2 = new Entity(array('id' => 2));
        $collection2 = new Collection();
        $collection2->append($entity2);

        $expectedCollection = new Collection();
        $expectedCollection->offsetSet($entity1->getId(), $entity1);
        $expectedCollection->offsetSet($entity2->getId(), $entity2);

        $collection1->addCollection($collection2, 'id', true);

        $this->assertEquals($expectedCollection, $collection1);
    }

    public function testGetByAttributeValueWithEmptyCollectionReturnDefaultEntity() {
        $collection = new Collection();

        $this->assertEquals(new Entity(), $collection->getByAttributeValue(1));
    }

    public function testGetByAttributeValueWithEmptyCollectionReturnEntity() {
        $collection = new Collection();

        $this->assertEquals(new EntityWithName(), $collection->getByAttributeValue(1, 'id', 'T4webBaseTest\Domain\Assets\EntityWithName'));
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Entity T4webBase\Domain\Entity has no method getDfgjksn
     */
    public function testGetByAttributeValueWithThrowException() {
        $entity = new Entity();
        $collection = new Collection();
        $collection->append($entity);

        $collection->getByAttributeValue(1, 'dfgjksn');
    }

    public function testGetByAttributeValueReturnDefaultEntity() {
        $entity = new Entity(array('id' => 12));
        $collection = new Collection();
        $collection->append($entity);

        $this->assertEquals(new Entity(), $collection->getByAttributeValue(10));
    }

    public function testGetByAttributeValue() {
        $id = 12;
        $entity = new Entity(array('id' => $id));
        $collection = new Collection();
        $collection->append($entity);

        $this->assertEquals($entity, $collection->getByAttributeValue($id));
    }

    /**
     * @dataProvider GetByAttributeValueProvider
     */
    public function testGetByAttributeValue_WithHtmlEntities($value, $collectionValue) {
        $entity = new Entity(array('id' => $collectionValue));
        $collection = new Collection();
        $collection->append($entity);

        $this->assertEquals($entity, $collection->getByAttributeValue($value));
    }

    public function GetByAttributeValueProvider() {
        return array(
            array('name\'1', 'name\'1'),
            array(htmlentities('name\'1'), 'name\'1'),
            array('name\'1', htmlentities('name\'1')),
            array(htmlentities('name\'1'), htmlentities('name\'1')),
        );
    }

    public function testGetAllByAttributeValueWithEmptyCollectionReturnEmptyCollection() {
        $collection = new Collection();

        $this->assertEquals(new Collection(), $collection->getAllByAttributeValue(1));
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Entity T4webBase\Domain\Entity has no method getDfgjksn
     */
    public function testGetAllByAttributeValueWithThrowException() {
        $entity = new Entity();
        $collection = new Collection();
        $collection->append($entity);

        $collection->getAllByAttributeValue(1, 'dfgjksn');
    }

    public function testGetAllByAttributeValue() {
        $id = 12;
        $entity = new Entity(array('id' => $id));
        $collection = new Collection();
        $collection->append($entity);

        $expectedCollection = new Collection();
        $expectedCollection->offsetSet($entity->getId(), $entity);

        $this->assertEquals($expectedCollection, $collection->getAllByAttributeValue($id));
    }

    public function testGetAllByAttributeValueArray() {
        $id = array(12);
        $entity = new Entity(array('id' => $id[0]));
        $collection = new Collection();
        $collection->append($entity);

        $expectedCollection = new Collection();
        $expectedCollection->offsetSet($entity->getId(), $entity);

        $this->assertEquals($expectedCollection, $collection->getAllByAttributeValue($id));
    }

    /**
     * @dataProvider GetAllByAttributeValueProvider
     */
    public function testGetAllByAttributeValue_WithHtmlEntities($value, $collectionValue) {
        $entity = new Entity(array('id' => $collectionValue));
        $collection = new Collection();
        $collection->append($entity);

        $expectedCollection = new Collection();
        $expectedCollection->offsetSet($entity->getId(), $entity);

        $this->assertEquals($expectedCollection, $collection->getAllByAttributeValue($value));
    }

    public function GetAllByAttributeValueProvider() {
        return array(
            array('name1', 'name1'),
            array(htmlentities('name\'1'), 'name\'1'),
            array('name\'1', htmlentities('name\'1')),
            array(htmlentities('name1'), htmlentities('name1')),
            array(array('name\'1', 'name2'), 'name\'1'),
            array(array(htmlentities('name1'), 'name2'), 'name1'),
            array(array('name\'1', 'name2'), htmlentities('name\'1')),
            array(array(htmlentities('name\'1'), 'name2'), htmlentities('name\'1')),
        );
    }

    public function testGetAllByPages_EmptyCollection_ReturnNewCollection() {
        $collection = new Collection();
        $page = 1;
        $limit = 20;

        $this->assertEquals(new Collection(), $collection->getAllByPages($page, $limit));
    }

    public function testGetAllByPages_NotEmptyCollection_ReturnCollectionByPages() {
        $page = 2;
        $limit = 2;
        $entity1 = new Entity(array('id' => 1));
        $entity2 = new Entity(array('id' => 2));
        $entity3 = new Entity(array('id' => 3));
        $collection = new Collection();
        $collection->offsetSet($entity1->getId(), $entity1);
        $collection->offsetSet($entity2->getId(), $entity2);
        $collection->offsetSet($entity3->getId(), $entity3);

        $collectionByPages = new Collection();
        $collectionByPages->offsetSet($entity3->getId(), $entity3);

        $this->assertEquals($collectionByPages, $collection->getAllByPages($page, $limit));
    }
}
