<?php

namespace Base\InputFilter\Element;

use Zend\Validator;

class InArray extends Element {

    public function __construct(array $data, $name = null) {
        parent::__construct($name);

        $validator = new Validator\InArray(array('haystack' => $data));
        $validator->setMessages(array(
            Validator\InArray::NOT_IN_ARRAY => "Поле не может иметь заданное значение"
        ));

        $this->getValidatorChain()
             ->attach($validator);
        
    }
    
}