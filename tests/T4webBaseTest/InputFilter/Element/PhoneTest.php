<?php

namespace T4webBaseTest\InputFilter\Element;

use T4webBase\InputFilter\Element\Phone;
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

    /**
     * @dataProvider providerWithInitData
     */
    public function testIsValid_InitPhoneWithData_Return($pattern, $errorPattern, $value, $result) {
        $phone = new Phone('phone', $pattern, $errorPattern);
        $phone->setValue($value);
        $context['phone'] = $value;
        $errorMessages = array(true => '', false => 'Неверный формат номера: '. $errorPattern);

        $this->assertEquals($result, $phone->isValid($context));
        $this->assertEquals($errorMessages[$result], $phone->getErrorMessage());
    }

    public function providerWithInitData() {
        return array(
            array('/^\+38 \(\d{3}\) \d{3}-\d{2}-\d{2}$/', '+38 (###) ###-##-##', '+38 (123) 123-12-12', true),
            array('/^\+38 \(\d{3}\) \d{3}-\d{2}-\d{2}$/', '+38 (###) ###-##-##', '(123) 123-12-12', false),
            array('/^\+38 \(\d{3}\) \d{3}-\d{2}-\d{2}$/', '+38 (###) ###-##-##', '', false),

            array('/^\(\d{3}\) \d{3}-\d{2}-\d{2}$/', '(###) ###-##-##', '+38 (123) 123-12-12', false),
            array('/^\(\d{3}\) \d{3}-\d{2}-\d{2}$/', '(###) ###-##-##', '(123) 123-12-12', true),
            array('/^\(\d{3}\) \d{3}-\d{2}-\d{2}$/', '(###) ###-##-##', '', false),
        );
    }
    
}
