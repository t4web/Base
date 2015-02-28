<?php

namespace BaseTest\InputFilter\Element;

use Base\InputFilter\Element\Float;

class FloatTest extends \PHPUnit_Framework_TestCase {
    
    protected $floatElement;
    
    public function setUp() {
        $this->floatElement = new Float();
    }
    
    public function testConstruct() {
        $this->assertInstanceOf('Base\InputFilter\Element\Element', $this->floatElement);
    }
    
    /**
     * @dataProvider provider
     */
    public function testIsValid($value, $expectedResult, $expectedValue) {
        $this->floatElement->setValue($value);
        
        $this->assertEquals($expectedResult, $this->floatElement->isValid());
        $this->assertSame($expectedValue, $this->floatElement->getValue());
    }
    
    public function provider() {
        return array(
            array(1, true, 1),
            array(123, true, 123),
            array(1.1, true, 1.1),
            array('1,01', true, '1,01'),
            array('1001,01', true, '1001,01'),
            array(0.01, true, 0.01),
            
            array(0.0125, false, null), // потому что жестко два знака после запятой!
            array(2.0111, false, null), // потому что жестко два знака после запятой!
            
            array('a', false, null),
            array('a.', false, null),
            array('a1', false, null),
            array('.1', false, null),
            array("2a1", false, null),
            array("2!1", false, null),
            array('1d', false, null),
            array('1.d', false, null),
            array('1.23d', false, null),
        );
    }
}
