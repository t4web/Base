<?php

namespace T4webBaseTest\InputFilter;

use T4webBase\InputFilter\InvalidInputError;
use T4webBase\InputFilter\ErrorAwareTrait;

class ErrorAwareTraitTest extends \PHPUnit_Framework_TestCase {
    use ErrorAwareTrait;

    private $dataErrors = array(
        'name' => 'name',
        'surname' => 'surname'
    );

    public function setUp() {
        $this->setErrors($this->dataErrors);
    }

    public function testSetErrors() {
        $this->assertAttributeEquals(new InvalidInputError($this->dataErrors), 'errors', $this);
    }

    public function testGetErrors() {
        $this->assertEquals(new InvalidInputError($this->dataErrors), $this->getErrors());
    }
}
