<?php

namespace T4webBase\InputFilter\Element;

use Zend\Validator;

class Phone extends Element {

    public function __construct($name = null) {
        parent::__construct($name);
    }

    public function isValid($context = null) {
        $name = $this->getName();
        $result = parent::isValid($context);
        $pattern = '/^\+38 \(\d{3}\) \d{3}-\d{2}-\d{2}$/';
        if (!preg_match($pattern, $context[$name])) {
            $this->setErrorMessage('Неверный формат номера: +38 (###) ###-##-##');
            $result = false;
        }

        return $result;
    }
}
