<?php

namespace BaseTest\Domain;

use Base\Domain\Entity;

class EntityTest extends \PHPUnit_Framework_TestCase {
    
    public function testCanCreate() {
        $entity = new Entity();
        $this->assertObjectHasAttribute('id', $entity);
        $this->assertInstanceOf('Base\Object\HydratingObject', $entity);
    }
    
    public function testGetId() {
        $entity = new Entity();
        $reflectionClass = new \ReflectionClass($entity);
        $properttyId = $reflectionClass->getProperty('id');
        $properttyId->setAccessible(true);
        $properttyId->setValue($entity, 1);
        
        $this->assertEquals(1, $entity->getId());
    }
    
    public function testConstructor() {
        $entity = new Entity(array('id' => '123'));
        $this->assertAttributeEquals('123', 'id', $entity);
    }
}
