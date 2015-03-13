<?php

namespace T4webBaseTest\Filter;

use T4webBase\Filter\String;

class StringTest extends \PHPUnit_Framework_TestCase {

    private $filter;

    public function setUp() {
        $this->filter = new String();
    }

    public function testFilterInstanseOfAbstractFilter() {
        $this->assertInstanceOf('Zend\Filter\AbstractFilter', $this->filter);
    }

    public function testFilter() {
        $value = "super 'value'";
        $expected = "super &#039;value&#039;";

        $this->assertEquals($expected, $this->filter->filter($value));
    }

}