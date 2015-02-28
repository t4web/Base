<?php

namespace Base\InputFilter\Element;

use Zend\Validator;

class LocalhostDomain extends Element {

    public function __construct($name = null, $allow = Validator\Hostname::ALLOW_LOCAL) {

        $hostNameValidator = new Validator\Hostname(array('allow' => $allow));
        $hostNameValidator->setMessages(array(
            Validator\Hostname::CANNOT_DECODE_PUNYCODE => "The input appears to be a DNS hostname but the given punycode notation cannot be decoded",
            Validator\Hostname::INVALID => "Неверный тип поля",
            Validator\Hostname::INVALID_DASH => "Домен содержит дефис в неверном месте",
            Validator\Hostname::INVALID_HOSTNAME => "Некорректно задано имя хоста",
            Validator\Hostname::INVALID_HOSTNAME_SCHEMA => "Ошибка в сравнении домена",
            Validator\Hostname::INVALID_LOCAL_NAME => "Некоректное значение для домена",
            Validator\Hostname::INVALID_URI => "Недействительный домен",
            Validator\Hostname::IP_ADDRESS_NOT_ALLOWED => "IP не допускаются",
            Validator\Hostname::LOCAL_NAME_NOT_ALLOWED => "Домены для локальных сетей запрещены",
            Validator\Hostname::UNDECIPHERABLE_TLD => "Ошибка распознания хоста",
            Validator\Hostname::UNKNOWN_TLD => "Неопознанное имя хоста",
        ));

        $this->getValidatorChain()
            ->attach($hostNameValidator, true);
        
        parent::__construct($name);
    }
    
}
