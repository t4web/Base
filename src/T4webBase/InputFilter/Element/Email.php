<?php

namespace T4webBase\InputFilter\Element;

use Zend\Validator;

class Email extends Element {

    public function __construct($name = null) {
        $hostNameValidator = new Validator\Hostname();
        $hostNameValidator->setMessages(array(
            Validator\Hostname::INVALID => "Неверный тип поля",
            Validator\Hostname::LOCAL_NAME_NOT_ALLOWED => "",
            Validator\Hostname::UNKNOWN_TLD => "Неопознанное имя хоста",
            Validator\Hostname::INVALID_HOSTNAME => "Некорректно задано имя хоста",
            Validator\Hostname::CANNOT_DECODE_PUNYCODE => "",
            Validator\Hostname::INVALID_DASH => "",
            Validator\Hostname::INVALID_HOSTNAME_SCHEMA => "",
            Validator\Hostname::INVALID_LOCAL_NAME => "",
            Validator\Hostname::INVALID_URI => "",
            Validator\Hostname::IP_ADDRESS_NOT_ALLOWED => "",
            Validator\Hostname::UNDECIPHERABLE_TLD => "",
        ));

        $validator = new Validator\EmailAddress(array('hostnameValidator' => $hostNameValidator));
        $validator->setMessages(array(
            Validator\EmailAddress::INVALID => "Неверный тип поля",
            Validator\EmailAddress::INVALID_FORMAT => "Неверный формат поля. Используйте стандартный формат local-part@hostname",
            Validator\EmailAddress::INVALID_HOSTNAME => "'%hostname%' некорректное имя хоста для email адреса",
            Validator\EmailAddress::INVALID_LOCAL_PART => "'%localPart%' некорректное имя для email адреса",
            Validator\EmailAddress::LENGTH_EXCEEDED => "Значение превышает допустимые размеры поля",
            Validator\EmailAddress::INVALID_MX_RECORD => "",
            Validator\EmailAddress::INVALID_SEGMENT => "",
            Validator\EmailAddress::DOT_ATOM => "",
            Validator\EmailAddress::QUOTED_STRING => "",
        ));

        $this->getValidatorChain()
             ->attach($validator, true);
        
        parent::__construct($name);
    }

}
