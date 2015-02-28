<?php

namespace BaseTest\InputFilter\Element;

use Base\InputFilter\Element\Phone;
use Zend\Validator;

class PhoneTest extends \PHPUnit_Framework_TestCase {
    
    /**
     * @dataProvider DataProvider
     */
    public function testIsValid_LocalHref($value, $result) {
        $phone = new Phone();
        $phone->setValue($value);
        $phone->setName('phone');
        $context['phone'] = $value;
        $errorMessages = array(true => '', false => 'Неверный формат номера: +38 (###) ###-##-##');

        $this->assertEquals($result, $phone->isValid($context));
        $this->assertEquals($errorMessages[$result], $phone->getErrorMessage());
    }

    public function DataProvider() {
        return array(
            array('+38 (123) 123-12-12', true),
            array('', false),
            array('4564654', false),
        );
    }
    
}
