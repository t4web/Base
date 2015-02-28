<?php

namespace Base\InputFilter\Element;

use Zend\Validator;

class Dt extends Element {

    public function __construct($name = null) {

        $validator = new Validator\Date(array('format' => 'Y-m-d H:i:s'));
        $validator->setMessages(array(
            Validator\Date::INVALID => "Неверный тип поля. Значение может быть числовым, строковым или датой",
            Validator\Date::INVALID_DATE => "Некорректное значение даты",
            Validator\Date::FALSEFORMAT => "Неверный формат даты"
        ));

        $this->getValidatorChain()
            ->attach($validator);
        
        parent::__construct($name);
    }
    
}
