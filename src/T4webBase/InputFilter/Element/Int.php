<?php

namespace T4webBase\InputFilter\Element;

use Zend\Validator;

class Int extends Element {

    public function __construct($name = null) {
        $validator = new Validator\Digits(array('min' => 0));
        $validator->setMessages(array(
            Validator\Digits::INVALID => "Значение обязательное и не может быть пустым",
            Validator\Digits::NOT_DIGITS => "Значение обязательное и не может быть пустым",
            Validator\Digits::STRING_EMPTY => "Значение обязательное и не может быть пустым",
        ));

        $this->getValidatorChain()
             ->attach($validator);

        parent::__construct($name);
    }
    
}
