<?php

namespace T4webBaseTest\Object;

use T4webBaseTest\Object\TestAsset\TestableClass;

class HydratingObjectTest extends \PHPUnit_Framework_TestCase {

    public function testPopulatePartialy() {
        
        // initialize
        $protectedPropertyValue = 'protectedProperty value';
        
        $array = array(
            'protectedProperty' => $protectedPropertyValue,
        );
        
        // fill object from array
        $object = new TestableClass();
        $object->populate($array);
        
        // get object reflection
        $objectReflection = new \ReflectionObject($object);
        
        // assert value of object property 'publicProperty'
        $this->assertEquals(null, $object->publicProperty);
        
        // assert value of object property 'protectedProperty'
        $protectedProperty = $objectReflection->getProperty('protectedProperty');
        $protectedProperty->setAccessible(true);
        $this->assertEquals($protectedPropertyValue, $protectedProperty->getValue($object));
        
        // assert value of object property 'privateProperty'
        $privateProperty = $objectReflection->getProperty('privateProperty');
        $privateProperty->setAccessible(true);
        $this->assertEquals(null, $privateProperty->getValue($object));
    }
    
    
    public function testPopulateFull() {
        
        // initialize
        $publicPropertyValue    = 'publicProperty value';
        $protectedPropertyValue = 'protectedProperty value';
        $privatePropertyValue   = 'privateProperty value';
        
        $array = array(
            'publicProperty'    => $publicPropertyValue,
            'protectedProperty' => $protectedPropertyValue,
            'privateProperty'   => $privatePropertyValue,
        );
        
        // fill object from array
        $object = new TestableClass();
        $object->populate($array);
        
        // get object reflection
        $objectReflection = new \ReflectionObject($object);
        
        // assert value of object property 'publicProperty'
        $this->assertEquals($publicPropertyValue, $object->publicProperty);
        
        // assert value of object property 'protectedProperty'
        $protectedProperty = $objectReflection->getProperty('protectedProperty');
        $protectedProperty->setAccessible(true);
        $this->assertEquals($protectedPropertyValue, $protectedProperty->getValue($object));
        
        // assert value of object property 'privateProperty'
        $privateProperty = $objectReflection->getProperty('privateProperty');
        $privateProperty->setAccessible(true);
        $this->assertEquals(null, $privateProperty->getValue($object));
    }
    
    
    public function testExtractFull() {
        
        // initialize
        $publicPropertyValue = 'publicProperty value';
        $protectedPropertyValue = 'protectedProperty value';
        $privatePropertyValue = 'privateProperty value';
        
        $object = new TestableClass();
        $object->publicProperty = $publicPropertyValue;
        
        // get object reflection
        $objectReflection = new \ReflectionObject($object);
        
        // set value for property 'protectedProperty'
        $protectedProperty = $objectReflection->getProperty('protectedProperty');
        $protectedProperty->setAccessible(true);
        $protectedProperty->setValue($object, $protectedPropertyValue);
        
        // set value for property 'privateProperty'
        $privateProperty = $objectReflection->getProperty('privateProperty');
        $privateProperty->setAccessible(true);
        $privateProperty->setValue($object, $privatePropertyValue);
        
        // set object state to array
        $stateArray = $object->extract(array(
            'publicProperty',
            'protectedProperty',
            'privateProperty',
        ));
        
        // assertion
        $expectedStateArray = array(
            'publicProperty' => $publicPropertyValue,
            'protectedProperty' => $protectedPropertyValue,
        );
        
        $this->assertEquals($stateArray, $expectedStateArray);
    }
    
    public function testExtractPartialy() {
        
        // initialize
        $publicPropertyValue = 'publicProperty value';
        $protectedPropertyValue = 'protectedProperty value';
        $privatePropertyValue = 'privateProperty value';
        
        $object = new TestableClass();
        $object->publicProperty = $publicPropertyValue;
        
        // get object reflection
        $objectReflection = new \ReflectionObject($object);
        
        // set value for property 'protectedProperty'
        $protectedProperty = $objectReflection->getProperty('protectedProperty');
        $protectedProperty->setAccessible(true);
        $protectedProperty->setValue($object, $protectedPropertyValue);
        
        // set value for property 'privateProperty'
        $privateProperty = $objectReflection->getProperty('privateProperty');
        $privateProperty->setAccessible(true);
        $privateProperty->setValue($object, $privatePropertyValue);
        
        // set object state to array
        $stateArray = $object->extract(array(
            'protectedProperty',
        ));
        
        // assertion
        $expectedStateArray = array(
            'protectedProperty' => $protectedPropertyValue,
        );
        
        $this->assertEquals($stateArray, $expectedStateArray);
    }
    
    public function testExtractFullWithoutSpecifyingProperties() {
        
        // initialize
        $publicPropertyValue = 'publicProperty value';
        $protectedPropertyValue = 'protectedProperty value';
        $privatePropertyValue = 'privateProperty value';
        
        $object = new TestableClass();
        $object->publicProperty = $publicPropertyValue;
        
        // get object reflection
        $objectReflection = new \ReflectionObject($object);
        
        // set value for property 'protectedProperty'
        $protectedProperty = $objectReflection->getProperty('protectedProperty');
        $protectedProperty->setAccessible(true);
        $protectedProperty->setValue($object, $protectedPropertyValue);
        
        // set value for property 'privateProperty'
        $privateProperty = $objectReflection->getProperty('privateProperty');
        $privateProperty->setAccessible(true);
        $privateProperty->setValue($object, $privatePropertyValue);
        
        // set object state to array
        $stateArray = $object->extract();
        
        // assertion
        $expectedStateArray = array(
            'publicProperty' => $publicPropertyValue,
            'protectedProperty' => $protectedPropertyValue,
        );
        
        $this->assertEquals($stateArray, $expectedStateArray);
    }
    
    public function testExtractNotExistingPropertiesWillReturnEmptyArray() {
        
        $object = new TestableClass();
        $object->publicProperty = 'publicProperty value';
        
        // set object state to array
        $stateArray = $object->extract(array('notExistingProperty'));
        
        $this->assertEquals(array(), $stateArray);
    }
    
}
