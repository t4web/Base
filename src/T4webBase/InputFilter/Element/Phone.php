<?php

namespace T4webBase\InputFilter\Element;

use Zend\Validator;

class Phone extends Element {

    private $pattern;

    public function __construct($name = null, $pattern = '/^\+38 \(\d{3}\) \d{3}-\d{2}-\d{2}$/')
    {
        parent::__construct($name);
        $this->pattern = $pattern;
    }

    public function isValid($context = null)
    {
        $name = $this->getName();
        $result = parent::isValid($context);

        if (!preg_match($this->pattern, $context[$name])) {
            $this->setErrorMessage('Неверный формат номера: +38 (###) ###-##-##');
            $result = false;
        }

        return $result;
    }
}
