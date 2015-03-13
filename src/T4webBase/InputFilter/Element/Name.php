<?php

namespace T4webBase\InputFilter\Element;

use Zend\Validator;
use T4webBase\Filter\String as FilterString;

class Name extends Element {

    public function __construct($name = null, $min = 1, $max = 255) {

        $validatorStringLength = new Validator\StringLength(array(
            'min' => $min,
            'max' => $max,
        ));
        $validatorStringLength->setMessages(array(
            Validator\StringLength::INVALID => "Неверный формат поля",
            Validator\StringLength::TOO_LONG => "Количество символов в поле не может быть больше ". $max,
            Validator\StringLength::TOO_SHORT => "Количество символов в поле не может быть меньше ". $min,
        ));

        $this->getFilterChain()
             ->attach(new FilterString());

        $this->getValidatorChain()
             ->attach($validatorStringLength);

        parent::__construct($name);
    }
}
