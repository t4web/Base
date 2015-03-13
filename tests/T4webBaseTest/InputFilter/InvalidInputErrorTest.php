<?php

namespace T4webBaseTest\InputFilter;

use T4webBase\InputFilter\InvalidInputError;

class InvalidInputErrorTest extends \PHPUnit_Framework_TestCase {

    private $nameError;
    private $surnameError;
    private $errors;

    public function setUp() {

        $this->nameError = array(
            'isEmpty' => 'The input is required and cannot be empty'
        );

        $this->surnameError = array(
            'otherKey' => 'Some other error'
        );

        $this->errors = array(
            'name' => $this->nameError,
            'surname' => $this->surnameError
        );
    }

    public function testConstructor() {
        $inputError = new InvalidInputError($this->errors);
        
        $this->assertAttributeSame($this->errors, 'errors', $inputError);
    }

    public function testHasErrors() {
        $inputError = new InvalidInputError($this->errors);

        $this->assertTrue($inputError->hasErrors('name'));
        $this->assertTrue($inputError->hasErrors('surname'));
        $this->assertFalse($inputError->hasErrors('someField'));
    }

    public function testGetErrors() {
        $inputError = new InvalidInputError($this->errors);

        $this->assertEquals($this->nameError, $inputError->getErrors('name'));
        $this->assertEquals($this->surnameError, $inputError->getErrors('surname'));
        $this->assertEquals(array(), $inputError->getErrors('someFieldXXX'));
        $this->assertEquals($this->errors, $inputError->getErrors());
    }
    
}
