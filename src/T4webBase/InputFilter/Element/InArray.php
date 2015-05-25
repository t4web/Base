<?php

namespace T4webBase\InputFilter\Element;

use Zend\Validator;

class InArray extends Element {

    public function __construct($name, array $data) {
        parent::__construct($name);

        $validator = new Validator\InArray(array('haystack' => $data));
        $validator->setMessages(array(
            Validator\InArray::NOT_IN_ARRAY => "Поле не может иметь заданное значение"
        ));

        $this->getValidatorChain()
                ->attach($validator);
    }
}