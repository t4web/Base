<?php

namespace BaseTest\InputFilter\Element;

use Base\InputFilter\Element\Price;

class PriceTest extends \PHPUnit_Framework_TestCase {

    protected $priceElement;

    public function setUp() {
        $this->priceElement = new Price();
    }

    public function testConstruct() {
        $this->assertInstanceOf('Base\InputFilter\Element\Element', $this->priceElement);
    }

    /**
     * @dataProvider provider
     */
    public function testIsValid($value, $expectedResult, $expectedValue) {
        $this->priceElement->setValue($value);

        $this->assertEquals($expectedResult, $this->priceElement->isValid());
        $this->assertSame($expectedValue, $this->priceElement->getValue());
    }

    public function provider() {
        return array(
            array(1, true, 1),
            array(123, true, 123),
            array(1.1, false, null),
            array(0.01, false, null),

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
            array('1..', false, null),
            array('..', false, null),
        );
    }
}
