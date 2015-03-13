<?php

namespace T4webBaseTest\InputFilter\Element;

use T4webBase\InputFilter\Element\NotEmptyValues;

class NotEmptyValuesTest extends \PHPUnit_Framework_TestCase {
    
    private $inputFilterElement;
    
    public function setUp() {
        $this->inputFilterElement = new NotEmptyValues();
        $this->inputFilterElement->setName('values');
    }
    
    /**
     * @dataProvider providerFalse
     */
    public function testIsValidWithBadFiltersReturnFalse($data, $message) {
        $this->assertFalse($this->inputFilterElement->isValid($data));
        $this->assertSame($message, $this->inputFilterElement->getMessages());
    }
    
    public function providerFalse() {
        return array(
            array(
                array(1),
                array('Значение не может быть пустым.')
            ),
            array(
                array('values' => array()),
                array('Значение не может быть пустым.')
            ),
            array( 
                array('values' => array(0 => '', 1 => '')),
                array('Значение не может быть пустым.')
            ),
        );
    }
    
    /**
     * @dataProvider providerTrue
     */
    public function testIsValidWithGoodFilters($data, $message) {

        $this->assertTrue($this->inputFilterElement->isValid($data));
        $this->assertSame($message, $this->inputFilterElement->getMessages());
    }
    
    public function providerTrue() {
        return array(
            array( 
                array('values' => array(0 => 'asa', 1 => '')),
                array()
            ),
            array( 
                array('values' => array(0 => 'asa', 1 => '', 2 => 'asa')),
                array()
            ),
            array( 
                array('values' => array(0 => '', 1 => '111', 2 => 'asa')),
                array()
            ),
        );
    }
}
