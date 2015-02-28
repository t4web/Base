<?php

namespace Base\InputFilter\Element;

use Zend\Validator;

class Ip extends Element {

    public function __construct($name = null) {
        $this->getValidatorChain()
            ->attach(new Validator\Ip());

        parent::__construct($name);
    }
}
