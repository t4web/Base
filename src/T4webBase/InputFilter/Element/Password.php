<?php

namespace T4webBase\InputFilter\Element;

use Zend\Validator;

class Password extends Element {

    public function __construct($name = null) {
        parent::__construct($name);
        
        $validatorNotEmpty = new Validator\NotEmpty();
        $validatorNotEmpty->setMessage(
                "Поле обязательное и не может быть пустым",
                Validator\NotEmpty::IS_EMPTY);
        
        $validator = new Validator\StringLength(array('min' => 4, 'max' => 50));
        $validator->setMessage('Значение не может быть меньше 4 символов', Validator\StringLength::TOO_SHORT);
        
        $this->getValidatorChain()
            ->attach($validatorNotEmpty, true)
            ->attach($validator);
    }
}
