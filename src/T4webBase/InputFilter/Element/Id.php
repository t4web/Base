<?php

namespace T4webBase\InputFilter\Element;

use Zend\Validator;

class Id extends Element {

    public function __construct($name = null) {
        $validatorGreaterThan = new Validator\GreaterThan(array('min' => 0));
        $validatorGreaterThan->setMessages(array(
            Validator\GreaterThan::NOT_GREATER => "Значение обязательное и не может быть пустым"
        ));

        $validatorDigits = new Validator\Digits(array('min' => 0));
        $validatorDigits->setMessages(array(
            Validator\Digits::INVALID => "Значение обязательное и не может быть пустым",
            Validator\Digits::NOT_DIGITS => "Значение обязательное и не может быть пустым",
            Validator\Digits::STRING_EMPTY => "Значение обязательное и не может быть пустым"
        ));

        $this->getValidatorChain()
             ->attach($validatorGreaterThan)
             ->attach($validatorDigits);

        parent::__construct($name);
    }
    
}
